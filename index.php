<?php
include 'config/database.php';

session_start();

$errorMsg = '';
$showSuccessMsg = $_SESSION['show-success'];
$_SESSION['show-success'] = false;

if (isset($_POST['login'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	if (strlen(trim($username)) === 0 || strlen(trim($password)) === 0) {
		// Stop submission if one or more input is invalid
		$errorMsg = 'One or more of your input is invalid';
		goto ifEnd;
	}
	$errorMsg = '';

	$query = "SELECT * FROM `user-data` WHERE username='$username' and password='$password'";

	$result = mysqli_query($conn, $query);

	if (mysqli_num_rows($result)) {
		$result = mysqli_fetch_assoc($result);

		$_SESSION['first_name'] = $result['first_name'];
		$_SESSION['last_name'] = $result['last_name'];
		$_SESSION['email'] = $result['email'];
		$_SESSION['uID'] = $result['user-id'];

		$errorMsg = '';
		header("Location: app/views/home.php");
	} else {
		$errorMsg = 'Incorrect username or password';
	}
}

// If form submitted, insert values into the database.
if (isset($_POST['signup-btn'])) {
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];

	if (strlen(trim($firstname)) === 0 || strlen(trim($lastname)) === 0 || strlen(trim($username)) === 0 || strlen(trim($password)) === 0 || strlen(trim($phone)) === 0 || strlen(trim($email)) === 0) {
		// Stop submission if one or more input is invalid
		$errorMsg = 'One or more of your input is invalid';

		goto ifEnd;
	}
	$errorMsg = '';

	$query = "INSERT INTO `user-data` (`first_name`, `last_name`, `username`, `password`, `phone`, `email`) VALUES ('$firstname','$lastname', '$username', '$password', '$phone', '$email')";

	$result = mysqli_query($conn, $query);

	if ($result) {
    $_SESSION['show-success'] = true;
    header("Location: index.php?success= Yayy 🥳. You have been successfully registered");
    // goto ifEnd;
	} else {
    $_SESSION['show-success'] = false;
		$errorMsg = 'Unable to register you right now';
	}
}



ifEnd:
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include 'public/header.php' ?>

    <!-- Title -->
    <title>Get started</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="./public/css/global.css" />
    <link rel="stylesheet" href="./public/css/index.css" />
    
    <!-- Scripts -->
    <script src="public/js/global.js" defer></script>
  </head>
  <body>
    <!-- Nav start -->
    <nav class="container">
      <a href="index.php" class="logo">Booker</a>

      <section>
        <ul>
          <li><a href="public/guide.html">How it works</a></li>
        </ul>
        <button><a href="./admin/login.php">Admin</a></button>
      </section>
    </nav>
    <!-- Nav end -->
    <div class="message">
      <?php if ($errorMsg) : ?>
        <div class="error--container">
          <img src="assets/svgs/error.svg" alt="!" /> <?php echo $errorMsg ?>
        </div>
			<?php endif; ?>

			<?php if ($showSuccessMsg) : ?>
        <div class="success--container">
          <img src="assets/svgs/success.svg" alt="!" />
          <div><?php echo $_GET['success'] ?></div>
        </div>
			<?php endif; ?>
    </div>
    <!-- Main start -->
    <main class="container content">
      <h1>
        Ship your <span>package</span> anywhere. Simple, easy and
        <em>fast</em> 💨.
      </h1>
      <section class="forms">
        <div id="login" class="form" data-login="login">
          <h2>Login</h2>
          <form action="" method="post" autocomplete="off">
            <div class="email">
              <label for="username">Username</label>
              <input type="text" name="username" id="username" placeholder="🏢 Username" />
            </div>
            <div class="password">
              <label for="pass">Password</label>
              <input
                type="password" name="password"
                id="pass"
                placeholder="🔑 input your password"
              />
            </div>
          <div class="form-act">
            <span
              >No account? signup
              <a href="#" class="login" data-for-login="login">here</a></span
            >
            <button type="submit" name="login">Find me</button>
          </div>
        </form>
        </div>

        <div id="signup" class="form signup" data-signup="signup">
          <h2>Sign up</h2>
          <form action="" method="post" autocomplete="off" class="signup form">
            <div class="first-name">
              <label for="first-name">First Name</label>
              <input type="text" name="firstname" id="first-name" placeholder="🤵 John" />
            </div>
            <div class="last-name">
              <label for="last-name">Last Name</label>
              <input type="text" name="lastname" id="last-name" placeholder="🧓 Doe" />
            </div>
            <div class="username">
              <label for="username">Username</label>
              <input
                type="text" name="username"
                id="username"
                placeholder="🏢 Enter your desired username"
              />
            </div>
            <div class="password">
              <label for="pass">Password</label>
              <input
                type="password" name="password"
                id="pass"
                placeholder="🔑 input your password"
              />
            </div>
            <div class="phone">
              <label for="contact">Phone</label>
              <input type="text" name="phone" id="phone" placeholder="📞 +234 8124159042" />
            </div>
            <div class="mail">
              <label for="email">Email address</label>
              <input
                type="email" name="email"
                id="email"
                placeholder="📧 example@gmail.com"
              />
            </div>
          <div class="form-act">
            <span
              >Old user? login
              <a href="#" class="signup" data-for-signup="signup">here</a></span
            >
            <button type="submit" name="signup-btn">Add me</button>
          </div>
        </form>
        </div>
      </section>
    </main>
    <!-- Main end -->
  </body>
</html>

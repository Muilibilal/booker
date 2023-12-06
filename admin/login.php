<?php
include '../config/database.php';
session_start();

if (isset($_POST['login'])) {
	$username = $_POST['admin-id'];
	$password = $_POST['passcode'];

	if (strlen(trim($username)) === 0 || strlen(trim($password)) === 0) {
		$errorMsg = 'One or more of your input is invalid';

		// Stop submission if one or more input is invalid
		goto ifEnd;
	}
	$errorMsg = '';

	$query = "SELECT * FROM `admin` WHERE `admin-id`='$username' and passcode='$password'";
	$result = mysqli_query($conn, $query);

	if (mysqli_num_rows($result)) {
		$result = mysqli_fetch_assoc($result);

    $_SESSION['admin-verify'] = $result['admin-id'];
		$errorMsg = '';
		$showSuccessMsg = true;
		header("Location: index.php");
	} else {
		$errorMsg = 'Incorrect username or password';
	}

}

ifEnd:
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include "../public/header.php" ?>
    <!-- Title -->
    <title>Admin login</title>
    <!-- CSS -->
    <link rel="stylesheet" href="../public/css/global.css">
    <link rel="stylesheet" href="styles/login.css">
  </head>
  <body>
    <main id="admin-main">
      <div class="admin-form">
        <h1>Admin page</h1>
        <form action="login.php" method="POST">
          <div>
            <label for="name">Admin ID</label>
            <input type="text" name="admin-id" placeholder="Enter ID" autocomplete="off" />
          </div>
          <div>
            <label for="name">Admin password</label>
            <input type="password" name="passcode" placeholder="Enter passcode" />
          </div>
          <button name="login">Login</button>
        </form>
      </div>
      <div class="hero-container">
      </div>
    </main>
  </body>
</html>

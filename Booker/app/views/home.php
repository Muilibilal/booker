    <?php 
      include '../../config/database.php';
      include '../../public/auth.php';
      include '../../public/new.php';


      $initails = $_SESSION['first_name'][0] . $_SESSION['last_name'][0];

      $allBookings = "SELECT * from `bookings` WHERE `user-id` = '$getUID'";
      $successfulBookings = "SELECT * from `bookings` WHERE `user-id` = '$getUID' and status = 'successful'";
      $pendingBookings = "SELECT * from `bookings` WHERE `user-id` = '$getUID' and status = 'pending'";
      $failedBookings = "SELECT * from `bookings` WHERE `user-id` = '$getUID' and status = 'failed'";

      $allResult = mysqli_query($conn, $allBookings);
      $successResult = mysqli_query($conn, $successfulBookings);
      $pendingResult = mysqli_query($conn, $pendingBookings);
      $failedResult = mysqli_query($conn, $failedBookings);

      $all = mysqli_fetch_all($allResult, 1);
      $success = mysqli_fetch_all($successResult, 1);
      $pending = mysqli_fetch_all($pendingResult, 1);
      $failed = mysqli_fetch_all($failedResult, 1);


      $avgDecider = 0;
      $allCount = 0;
      foreach ($all as $key => $value) {
        if ($value['amount'] > 0) {
          $avgDecider += $value['amount'];
          $allCount++;
        }
      }

      if ($allCount > 0) {
        $avgDecider = $avgDecider / $allCount;
      }
    ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
      include '../../public/header.php';
    ?>
    <!-- Title -->
    <title>Home</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../../public/css/global.css" />
    <link rel="stylesheet" href="../../public/css/home.css" />
    
    <!-- Scripts -->
    <script src="../../public/js/global.js" defer></script>
  </head>
  <body>
    <main>
      <aside class="operations">
        <div class="top">
          <a href="home.php" class="logo">Booker</a>

          <section class="controls">
            <button>➕ New booking</button>
            <ul>
              <li><a href="bookings.php">My bookings</a></li>
              <li><a href="payments.php">My payments</a></li>
              <li><a href="tracker.php">Tracker</a></li>
            </ul>
          </section>
        </div>

        <div class="bottom">
          <div class="user-thumb">
            <div class="avatar"><?php echo $initails ?></div>
            <div class="info">
              <p>Welcome, <?php echo $_SESSION["first_name"] ?> 👋</p>
              <span><?php echo $_SESSION['email']?></span>
            </div>
          </div>

          <a href="../../logout.php"
            >logout<img src="../../assets/svgs/logout.svg" alt="logout-icon"></a>
        </div>
      </aside>

      <section class="content">
        <div class="route">
          <h3>Home</h3>
        </div>

        <div class="cards">
          <div class="card">
            <span><?php echo count($success)?></span>
            <p>packages delivered</p>
            <img src="../../assets/icons/img1.png" />
          </div>
          <div class="card">
            <span><?php echo count($pending)?></span>
            <p>pending packages</p>
            <img src="../../assets/icons/box.png" />
          </div>
          <div class="card">
            <span><?php echo count($failed)?></span>
            <p>failed deliveries</p>
            <img src="../../assets/icons/img2.png" />
          </div>
        </div>

        <section class="overview recent">
          <h4>Top transactions</h4>
          <div class="activities">
            <table>
              <thead>
                <tr>
                  <th>Receiver</th>
                  <th>Transaction ID</th>
                  <th>Product</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Type</th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach ($all as $key => $value) {
                    if ($value["amount"] >= $avgDecider && strtolower($value["status"]) != "failed") { ?>
                    <tr>
                      <td><?php echo $value['receiver'] ?></td>
                      <td><?php echo $value['transactionId'] ?></td>
                      <td><?php echo $value['product'] ?></td>
                      <td><?php echo $value['amount'] ?></td>
                      <td><span id="status"><?php echo $value['status'] ?></span></td>
                      <td><?php echo $value['type'] ?></td>
                    </tr>
                  <?php }} ?>
                  <!-- <td>
                    <i class="fa-solid fa-pen-to-square"></i>
                    <i class="fa-solid fa-maximize"></i>
                    <i class="fa-solid fa-trash"></i>
                  </td> -->
              </tbody>
            </table>
            <div style="margin: 30px 10px; font-size: 1rem">
              <?php if (count($success) < 1 && count($pending) < 1){
                echo "Create bookings to see top transactions."; 
              }?>
					  </div>
          </div>
        </section>
      </section>
    </main>
  </body>
</html>

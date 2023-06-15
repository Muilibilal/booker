<?php 
  include '../../config/database.php';
  include '../../public/auth.php';
  include '../../public/new.php';
  include '../../public/modal.php';
  
  $initails = $_SESSION['first_name'][0] . $_SESSION['last_name'][0];

  $allBookings = "SELECT * from `bookings` WHERE `user-id` = '$getUID'";
  $allResult = mysqli_query($conn, $allBookings);
  $all = mysqli_fetch_all($allResult, 1);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php 
      include '../../public/header.php';
    ?>

    <!-- Title -->
    <title>My bookings</title>
    
    <!-- Links -->
    <link rel="stylesheet" href="../../public/css/global.css" />
    <link rel="stylesheet" href="../../public/css/modal.css" />

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
        <section class="overview payments">
          <div class="head">
            <h4>My payments</h4>
            <form action="">
              <input type="text" class="search" placeholder="Search payments" />
            </form>
          </div>
          <div class="activities">
            <table>
              <thead>
                <tr>
                  <th>S/N</th>
                  <th>Name</th>
                  <th>Transaction ID</th>
                  <th>Product</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($all as $key => $value) { 
                  if (strtolower($value['status']) === "sucessful" || strtolower($value['status']) === "pending") { 
                ?>
                  <tr>
                    <td><?php echo $key + 1 ?></td>
                    <td><?php echo $value['receiver'] ?></td>
                    <td><?php echo $value['transactionId'] ?></td>
                    <td><?php echo $value['product'] ?></td>
                    <td><?php echo $value['amount'] ?></td>
                    <td><span id="status"><?php echo $value['status'] ?></span></td>
                    <td class="actions">
                      <img class="open-modal" src="../../assets/svgs/success.svg" title="Payment" data-for-modal="delete-booking" data-for-el="delete-booking-id" data-for-del="<?php echo $value['transactionId']; ?>">
                      <img class="open-modal" src="../../assets/svgs/expand.svg" title="Expand" data-for-modal="details-booking" data-value =
                          '[
                              {"el-id": "view-receiver", "value": "<?php echo $value['receiver']; ?>"}, 
                              {"el-id": "view-product", "value": "<?php echo $value['product']; ?>"},  
                              {"el-id": "view-phone", "value": "<?php echo $value['phone']; ?>"},
                              {"el-id": "view-address", "value": "<?php echo $value['address']; ?>"},
                              {"el-id": "view-email", "value": "<?php echo $value['email']; ?>"},
                              {"el-id": "view-type", "value": "<?php echo $value['type']; ?>"},
                              {"el-id": "view-weight", "value": "<?php echo $value['weight']; ?>"},
                              {"el-id": "view-info", "value": "<?php echo $value['info']; ?>"}
                            ]'>
                      <img class="open-modal" src="../../assets/svgs/delete.svg" title="Delete" data-for-modal="delete-booking" data-for-el="delete-booking-id" data-for-del="<?php echo $value['transactionId']; ?>">
                    </td>
                  </tr>
                <?php                     
                  }}
                ?>
              </tbody>
            </table>
          </div>
        </section>
      </section>
    </main>
  </body>
</html>

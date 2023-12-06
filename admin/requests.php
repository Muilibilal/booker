<?php 
  include '../config/database.php';
  include 'inc/auth.php';
  include '../public/modal.php';
  $deleteCusErrorMsg = "";

  $allRequest = "SELECT * from `bookings` WHERE `payment_requests` = 'confirm-payment'";
  $allResult = mysqli_query($conn, $allRequest);
  $all = mysqli_fetch_all($allResult, 1);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment Requests</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../public/css/global.css">
    <link rel="stylesheet" href="../public/css/modal.css">
    <link rel="stylesheet" href="styles/global.css">

    <!-- Javascript -->
    <script src="../public/js/global.js" defer></script>
  </head>
  <body>
    <header>
      <span>Booker</span>
      <a href="logout.php">logout <img src="../assets/svgs/logout.svg" alt="logout-button"></a>
    </header>
    <main>
      <aside>
        <span>Homepage</span>
        <section class="controls">
          <ul>
            <li><a href="index.php">User bookings</a></li>
            <li><a href="requests.php">Payment requests</a></li>
          </ul>
        </section>
      </aside>
      <section class="activities interface">
        <h4>Payment Requests</h4>
        <table>
          <thead>
            <tr>
              <th>S/N</th>
              <th>Name</th>
              <th>Transaction ID</th>
              <th>Product</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Created By</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all as $key => $value) {?>
              <tr>
                <td><?php echo $key + 1 ?></td>
                <td><?php echo $value['receiver'] ?></td>
                <td><?php echo $value['transactionId'] ?></td>
                <td><?php echo $value['product'] ?></td>
                <td>₦ <?php echo $value['amount'] ?></td>
                <td class="status-parent"><span id="color"></span><span id="status"><?php echo $value['status'] ?></span></td>
                <td><?php echo $value['created-by'] ?></td>
                <td class="actions">
                  <img class="open-modal" src="../assets/svgs/expand.svg" alt="expand button" title="Expand" 
                    data-for-modal="details-booking" data-value =
                    '[
                        {"el-id": "view-transactionId", "value": "<?php echo $value['transactionId']; ?>"}, 
                        {"el-id": "view-receiver", "value": "<?php echo $value['receiver']; ?>"}, 
                        {"el-id": "view-product", "value": "<?php echo $value['product']; ?>"},  
                        {"el-id": "view-phone", "value": "<?php echo $value['phone']; ?>"},
                        {"el-id": "view-address", "value": "<?php echo $value['address']; ?>"},
                        {"el-id": "view-email", "value": "<?php echo $value['email']; ?>"},
                        {"el-id": "view-type", "value": "<?php echo $value['type']; ?>"},
                        {"el-id": "view-weight", "value": "<?php echo $value['weight']; ?>"},
                        {"el-id": "view-created", "value": "<?php echo $value['created-by']; ?>"},
                        {"el-id": "view-info", "value": "<?php echo $value['info']; ?>"}
                      ]'>
                  <img class="open-modal" src="../assets/svgs/approve.svg" alt="approve payment" title="Approve payment" data-for-modal="approve-booking" data-for-el="approve-booking-id" data-for-del="<?php echo $value['transactionId']; ?>">

                  <img class="open-modal" src="../assets/svgs/disapprove.svg" alt="reject payment" title="Reject payment" data-for-modal="reject-booking" data-for-el="reject-booking-id" data-for-del="<?php echo $value['transactionId']; ?>">
                </td>
              </tr>
            <?php }?>
          </tbody>
        </table>
        <div style="margin: 50px 0; font-size: 20px">
          <?php if (count($all) < 1){
            echo "No request at the moment. You're all caught up 🙂"; 
          }?>
        </div>
      </section>
    </main>
    <!-- Approve payment modal start -->
    <div class="overlay <?php echo $deleteCusErrorMsg ? 'active' : ''; ?> modal-booking" id="approve-booking">
      <div>
        
        <h2>Approve Payment</h2>
        <div class="modal">
          <img src="../assets/svgs/close.svg" alt="close" class="close-modal" data-for-modal="approve-booking" />

          <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
            <p>Are you sure you want to approve payment of</p>

            <input type="text" name="payment-id" id="approve-booking-id">

            <p>This action cannot be reversed</p>

            <div>
              <button type="button" class="close-modal" data-for-modal="delete-booking">
                Cancel
              </button>

              <button type="submit" name="approve-payment" class="approve--button">Approve</button>
            </div>
          </form>

          <?php if ($deleteCusErrorMsg) : ?>
            <div class="error--container">
              <img src="../assets/svgs/error.svg" alt="!" /> <?php echo $deleteCusErrorMsg ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <!-- Approve paymen modal end -->

    <!-- Reject payment modal start -->
    <div class="overlay <?php echo $deleteCusErrorMsg ? 'active' : ''; ?> modal-booking" id="reject-booking">
      <div>
        <h2>Reject Payment</h2>
        <div class="modal">
          <img src="../assets/svgs/close.svg" alt="close" class="close-modal" data-for-modal="reject-booking" />

          <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
            <p>Are you sure you want to reject payment of</p>

            <input type="text" name="payment-id" id="reject-booking-id">

            <p>This action cannot be reversed</p>

            <div>
              <button type="button" class="close-modal" data-for-modal="delete-booking">
                Cancel
              </button>

              <button type="submit" name="reject-payment" class="reject--button">Reject</button>
            </div>
          </form>

          <?php if ($deleteCusErrorMsg) : ?>
            <div class="error--container">
              <img src="../assets/svgs/error.svg" alt="!" /> <?php echo $deleteCusErrorMsg ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <!-- Reject paymen modal end -->

    <!-- Details modal start -->
    <div class="overlay" id="details-booking">
      <div>
        <div class="modal">
          <div class="modal-new-booking">
            <h2>Booking details</h2>
            <img src="../assets/svgs/close.svg" alt="close" class="close-modal" data-for-modal="details-booking" />
            <div class="details details-card">
              <form action="">
                <input type="hidden" id="view-transactionId">
                <div class="detail--group">
                  <div>
                    <h4>Receiver Name</h4>
                    <span id="view-receiver"></span>
                  </div>

                  <div>
                    <h4>Phone Number</h4>
                    <span id="view-phone"></span>
                  </div>
                </div>

                <div class="detail--group">
                  <div>
                    <h4>Address</h4>
                    <span id="view-address"></span>
                  </div>
                  <div>
                    <h4>Email</h4>
                    <span id="view-email"></span>
                  </div>
                </div>

                <div class="detail--group">
                  <div>
                    <h4>Product Name</h4>
                    <span id="view-product"></span>
                  </div>
                  <div>
                    <h4>Product Type</h4>
                    <span id="view-type"></span>
                  </div>
                </div>

                <div class="detail--group">
                  <div>
                    <h4>Weight</h4>
                    <span id="view-weight"></span>
                  </div>
                  <div>
                    <h4>Description</h4>
                    <span id="view-info"></span>
                  </div>
                  <div>
                    <h4>Created by</h4>
                    <span id="view-created"></span>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- <div class="confirm-parent inactive">
          <div class="details details-card payment">
            <form action="" method="POST">
              <h4>Approve payment of</h4>
                <input type="text" name="transaction-id" id="view-transactionId">
              <button type="submit" name="approve-payment" class="admin-approve payment-btn">Approve payment</button>
              <button type="submit" class="close">close</button>
            </form>
          </div>
        </div> -->
      </div>
    </div>
			<!-- Details modal end -->
  </body>
</html>

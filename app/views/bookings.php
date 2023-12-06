<?php 
  include '../../config/database.php';
  include '../../public/auth.php';
  include '../../public/new.php';
  include '../../public/modal.php';
  
  $initails = $_SESSION['first_name'][0] . $_SESSION['last_name'][0];

  $allBookings = "SELECT * from `bookings` WHERE `user-id` = '$getUID'";
  $allResult = mysqli_query($conn, $allBookings);
  $all = mysqli_fetch_all($allResult, 1);

  if (isset($_POST['search-booking'])) {
    // Establish a database connection (replace with your database credentials)
    $dsn = 'mysql:host=localhost;dbname=booker';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }

    // Get the search query from the user
    $searchQuery = $_POST['searched-text'] ?? false;

    if (!empty($searchQuery) && $searchQuery !== false) {
      // Prepare the SQL statement with a placeholder for the search query
      $sql = "SELECT * FROM bookings WHERE `user-id` = '$getUID' and product LIKE :searchQuery or `user-id` = '$getUID' and receiver LIKE :searchQuery";

      // Bind the search query to the placeholder
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':searchQuery', '%' . $searchQuery . '%');

      // Execute the query
      $stmt->execute();

      // Fetch the search results
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (!$results) {
        echo "Product not found. You are only allowed to search bookings by name or product";
      }

    }else{
      echo "Please enter a valid search query";
    }
  }
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
        <section class="overview bookings">
          <h4>All bookings</h4>
          <form action="bookings.php" method="POST">
            <input type="text" name="searched-text" value="" class="search" placeholder="Search bookings by name or product" />
            <button name="search-booking" type="submit">Search</button>
          </form>
          <div class="activities">
            <table>
              <thead>
                <tr>
                  <th>S/N</th>
                  <th>Receiver</th>
                  <th>Transaction ID</th>
                  <th>Product</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $displayBookings = !empty($results) ? $results : $all;
                foreach ($displayBookings as $key => $value) {?>
                    <tr>
                      <td><?php echo $key + 1 ?></td>
                      <td><?php echo $value['receiver'] ?></td>
                      <td><?php echo $value['transactionId'] ?></td>
                      <td><?php echo $value['product'] ?></td>
                      <td>₦ <?php echo $value['amount'] ?></td>
                      <td class="status-parent"><span id="color"></span><span id="status"><?php echo $value['status'] ?></span></td>
                      <td class="actions">
                        <img
                          class="open-modal" src="../../assets/svgs/edit.svg" alt="edit button" title="Edit" 
                          data-for-modal="edit-booking" data-value =
                          '[
                              {"el-id": "receiver", "value": "<?php echo $value['receiver']; ?>"}, 
                              {"el-id": "transId", "value": "<?php echo $value['transactionId']; ?>"}, 
                              {"el-id": "product", "value": "<?php echo $value['product']; ?>"},  
                              {"el-id": "phone", "value": "<?php echo $value['phone']; ?>"},
                              {"el-id": "address", "value": "<?php echo $value['address']; ?>"},
                              {"el-id": "email", "value": "<?php echo $value['email']; ?>"},
                              {"el-id": "type", "value": "<?php echo $value['type']; ?>"},
                              {"el-id": "weight", "value": "<?php echo $value['weight']; ?>"},
                              {"el-id": "info", "value": "<?php echo $value['info']; ?>"}
                            ]'
                        >
                        <img class="open-modal" src="../../assets/svgs/expand.svg" alt="expand button" title="Expand" 
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
                              {"el-id": "view-info", "value": "<?php echo $value['info']; ?>"}
                            ]'>
                        <img class="open-modal" src="../../assets/svgs/delete.svg" alt="delete button" title="Delete" data-for-modal="delete-booking" data-for-el="delete-booking-id" data-for-del="<?php echo $value['transactionId']; ?>">
                      </td>
                    </tr>
                  <?php }?>
              </tbody>
            </table>
            <div style="margin: 50px 0; font-size: 20px">
              <?php if (count($all) < 1){
                echo "Create bookings to perform more actions"; 
              }?>
					  </div>
          </div>
        </section>
      </section>
    </main>


    <!-- Edit modal start -->
    <div class="overlay <?php echo $editCusErrorMsg ? 'active' : ''; ?>" id="edit-booking">
      <div>
        <div class="modal">
          <div class="modal-new-booking">
            <h2>Edit booking</h2>
            <div class="details">
              <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" autocomplete="off">
                <input type="hidden" name="id" id="transId">
                <div class="detail detail-1">
                  <h3>Receiver details</h3>
                  <div class="receiver-name">
                    <input type="text" id="receiver" placeholder="🤵 Receiver name"  name="rec-name" required/>
                  </div>
                  <div class="phone">
                    <input
                      type="text"
                      id="phone"
                      placeholder="📞 phone number" name="rec-phone"
                     required/>
                  </div>
                  <div class="infos">
                    <input
                      type="text"
                      id="address"
                      placeholder="📧 Home address" name="rec-addr"
                     required/>
                  </div>
                  <div class="mail">
                    <input type="email" id="email" placeholder="📧 Email address (*Optional)" name="rec-email" value="" />
                  </div>
                </div>
                <div
                class="detail detail-2"
                style="border-left: 2px solid #afafaf; padding-left: 20px"
                >
                  <h3>Product details</h3>
                  <div class="receiver-name nest-input">
                    <input type="text" id="product" placeholder="Product Name" name="pdt-name" required/>
                    <select name="selection" tabindex="0">
                      <option>Type</option>
                      <option id="type" value="standard">Standard</option>
                      <option id="type" value="priority">Priority</option>
                      <option id="type" value="economy">Economy</option>
                      <option id="type" value="heavyweight">Heavyweight</option>
                      <option id="type" value="fragile">Fragile</option>
                    </select>
                  </div>
                  <div>
                    <input type="text" id="weight" placeholder="Weight (*Optional)" name="pdt-wght" value=""/>
                  </div>
                  <div class="more-details">
                    <textarea
                      name="desc"
                      id="info"
                      placeholder="Descriptions (*Optional)"
                    ></textarea>
                  </div>
                  <button type="submit" name="update-booking" class="book book-new">Update</button>
                  <button type="submit" class="close close-new">close</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

          <?php if ($editCusErrorMsg) : ?>
            <div class="error--container">
              <img src="../../assets/svgs/error.svg" alt="!" /> <?php echo $editCusErrorMsg ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <!-- Edit modal end -->

    <!-- Delete modal start -->
    <div class="overlay <?php echo $deleteCusErrorMsg ? 'active' : ''; ?>" id="delete-booking">
      <div>
        
        <h2>Delete booking</h2>
        <div class="modal">
          <img src="../../assets/svgs/close.svg" alt="close" class="close-modal" data-for-modal="delete-booking" />

          <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
            <p>Are you sure you want to delete booking with ID</p>

            <input type="text" name="delete-id" id="delete-booking-id">

            <p>This action cannot be reversed</p>

            <div>
              <button type="button" class="close-modal cancel--button" data-for-modal="delete-booking">
                Cancel
              </button>

              <button type="submit" name="delete-booking" class="delete--button">Delete</button>
            </div>
          </form>

          <?php if ($deleteCusErrorMsg) : ?>
            <div class="error--container">
              <img src="../../assets/svgs/error.svg" alt="!" /> <?php echo $deleteCusErrorMsg ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <!-- Delete modal end -->

    <!-- Details modal start -->
    <div class="overlay" id="details-booking">
      <div>
        <div class="modal">
          <div class="modal-new-booking">
            <h2>Booking details</h2>
            <img src="../../assets/svgs/close.svg" alt="close" class="close-modal" data-for-modal="details-booking" />
            <div class="details details-card">
              <form action="">
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
                </div>
                <!-- <button type="submit" name="payment-button" class="payment-btn">Make payment</button> -->
                <div class="open-confirm">Make payment</div>
              </form>
            </div>
          </div>
        </div>
        <div class="confirm-parent inactive">
          <div class="details details-card payment">
            <form action="" method="POST">
              <h4>Make payment for </h4>
                <input type="text" name="payment-id" id="view-transactionId">
              <button type="submit" name="confirm-payment" class="payment-btn">Confirm payment</button>
              <button type="submit" class="close">close</button>
            </form>
          </div>
        </div>
      </div>
    </div>
			<!-- Details modal end -->
  </body>
</html>

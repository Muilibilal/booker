<?php 
  include '../config/database.php';
  include 'inc/auth.php';
  include '../public/modal.php';
  
  $allBookings = "SELECT * from `bookings`";
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
      $sql = "SELECT * FROM bookings WHERE product LIKE :searchQuery or receiver LIKE :searchQuery or `created-by` LIKE :searchQuery";

      // Bind the search query to the placeholder
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':searchQuery', '%' . $searchQuery . '%');

      // Execute the query
      $stmt->execute();

      // Fetch the search results
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (!$results) {
        echo "Product not found. You are only allowed to search data by name or product";
      }

    }else{
      echo "Please enter a valid search query";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Homepage</title>

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
      <section class="activities interface overview">
          <div class="head">
            <h4>User Bookings</h4>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
              <input type="text" name="searched-text" value="" class="search" placeholder="Search user data by name or product" />
              <button name="search-booking" type="submit">Search</button>
            </form>
          </div>
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
            <?php 
            $displaySearch = !empty($results) ? $results : $all;
            foreach ($displaySearch as $key => $value) {?>
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
                        {"el-id": "view-receiver", "value": "<?php echo $value['receiver']; ?>"}, 
                        {"el-id": "view-product", "value": "<?php echo $value['product']; ?>"},  
                        {"el-id": "view-phone", "value": "<?php echo $value['phone']; ?>"},
                        {"el-id": "view-address", "value": "<?php echo $value['address']; ?>"},
                        {"el-id": "view-email", "value": "<?php echo $value['email']; ?>"},
                        {"el-id": "view-type", "value": "<?php echo $value['type']; ?>"},
                        {"el-id": "view-weight", "value": "<?php echo $value['weight']; ?>"},
                        {"el-id": "view-created", "value": "<?php echo $value['created-by']; ?>"},
                        {"el-id": "view-info", "value": "<?php echo $value['info']; ?>"},
                        {"el-id": "view-date", "value": "<?php echo $value['date-created']; ?>"},
                        {"el-id": "view-edited", "value": "<?php echo $value['last-edit']; ?>"}
                      ]'>
                  <img class="open-modal" src="../assets/svgs/delete.svg" alt="delete button" title="Delete" data-for-modal="delete-booking" data-for-el="delete-booking-id" data-for-del="<?php echo $value['transactionId']; ?>">
                </td>
              </tr>
            <?php }?>
          </tbody>
        </table>
        <div style="margin: 50px 0; font-size: 20px">
              <?php if (count($all) < 1){
                echo "No user data currently available"; 
              }?>
					  </div>
      </section>
    </main>
    <!-- Delete modal start -->
    <div class="overlay <?php echo $deleteCusErrorMsg ? 'active' : ''; ?>" id="delete-booking">
      <div>
        
        <h2>Delete booking</h2>
        <div class="modal">
          <img src="../assets/svgs/close.svg" alt="close" class="close-modal" data-for-modal="delete-booking" />

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
              <img src="../assets/svgs/error.svg" alt="!" /> <?php echo $deleteCusErrorMsg ?>
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
            <img src="../assets/svgs/close.svg" alt="close" class="close-modal" data-for-modal="details-booking" />
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
                <div class="detail--group">
                  <div>
                    <h4>Created by</h4>
                    <span id="view-created"></span>
                  </div>
                  <div>
                    <h4>Date created</h4>
                    <span id="view-date"></span>
                  </div>
                  <div>
                    <h4>Last edited</h4>
                    <span id="view-edited"></span>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
			<!-- Details modal end -->
  </body>
</html>

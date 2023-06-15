<!DOCTYPE html>
<html lang="en">
  <head>

    <?php 
      include '../../public/header.php';
      include '../../config/database.php';
      include '../../public/auth.php';
      include '../../public/new.php';
      include '../../public/modal.php';



      $initails = $_SESSION['first_name'][0] . $_SESSION['last_name'][0];
    ?>
    <!-- Title -->
    <title>My bookings</title>
    <!-- Links -->
    <!-- Leaflet start-->
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
      integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
      crossorigin=""
    />
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script
      defer
      src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
      integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
      crossorigin=""
    ></script>
    <!-- Leaflet end -->

    <!-- Stylesheet -->
    <link rel="stylesheet" href="../../public/css/global.css" />
    <link rel="stylesheet" href="../../public/css/tracker.css" />

    <!-- Scripts -->
    <script src="../../public/js/global.js" defer></script>
    <script src="../../public/js/tracker.js" defer></script>
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
              <p>
                Welcome,
                <?php echo $_SESSION["first_name"] ?>
                👋
              </p>
              <span><?php echo $_SESSION['email']?></span>
            </div>
          </div>

          <a href="../../logout.php"
            >logout<img src="../../assets/svgs/logout.svg" alt="logout-icon"></a>
        </div>
      </aside>
      <section class="content">
        <div id="map"></div>
        <div class="tracking-form">
          <form action="">
            <div>
              <div class="points start-location">
                <label for="startPoint">Pickup Location</label>
                <input type="text" name="st-loc" id="startPoint">
              </div>
              <div class="points destination-location">
                <label for="destinationPoint">Destination Location</label>
                <input type="text" name="dest-loc" id="destinationPoint">
              </div>
            </div>

            <button type="submit" id="convertButton">Get location</button>
          </form>
        </div>
        <div class="tracker-details"></div>
      </section>
    </main>
  </body>
</html>

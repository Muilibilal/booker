<?php
  include '../../config/database.php';
  
  $getUID = $_SESSION['uID'];
  if (isset($_POST['book'])) {
    $receiverName = $_POST['rec-name'];
    $receiverPhone = $_POST['rec-phone'];
    $receiverAddr = $_POST['rec-addr'];
    $receiverEmail = $_POST['rec-email'];
    $productName = $_POST['pdt-name'];
    $selection = $_POST['selection'];
    $productWght = $_POST['pdt-wght'];
    $productDesc = $_POST['desc'];

    // Transaction ID consists UserID, Date timestamp, receiverName and receiverPhone; 
    $newstr = substr_replace(strVal(strtotime("now")), '|', -4, 0);
    $dynamicID = substr($newstr, -4, strpos($newstr, "|"));

    $idName = $_SESSION['first_name'][0] . $_SESSION['last_name'][0];
    $createdBy = $_SESSION['first_name'] . " ". $_SESSION['last_name'];
    
    
    if (strlen(trim($receiverName)) === 0 || strlen(trim($receiverPhone)) === 0 || strlen(trim($receiverAddr)) === 0 || strlen(trim($productName)) === 0 || strlen(trim($selection)) === 0) {
      // Stop submission if one or more input is invalid
      $addCusErrorMsg = 'One or more of your input is invalid';
      
      goto end;
    }
    $addCusErrorMsg = '';
    $transDate = date("Y-m-d H:i:s");

    $transID = $idName . $getUID . $dynamicID . $receiverPhone;
    $amount = 0;
    if (strtolower($selection) == 'standard') {
      $amount = 15000;
    }else if(strtolower($selection) == 'priority'){
      $amount = 10000;
    }else if(strtolower($selection) == 'economy'){
      $amount = 5000;
    }else if(strtolower($selection) == 'heavyweight'){
      $amount = 20000;
    }else if(strtolower($selection) == 'fragile'){
      $amount = 7000;
    }

    $query = "INSERT INTO `bookings` (`receiver`,`transactionID`, `product`,`amount`, `status`, `payment_requests`, `phone`, `email`, `address`, `type`, `weight`, `user-id`, `info`, `date-created`,`created-by`) VALUES ('$receiverName', '$transID', '$productName', $amount, 'Pending', 'no-request', '$receiverPhone', '$receiverEmail', '$receiverAddr', '$selection', '$productWght', '$getUID', '$productDesc', '$transDate', '$createdBy')";

    $result = mysqli_query($conn, $query);

    if ($result) {
        $addCusErrorMsg = '';
        header("Location:$_SERVER[PHP_SELF]");
    } else {
        $addCusErrorMsg = 'Unable to add new customer';
    }
  }
  end:
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Title -->
    <title>New</title>
    <!-- Links -->
    <link rel="stylesheet" href="css/global.css" />
    <link rel="stylesheet" href="css/new.css" />
  </head>
  <body>
    <div class="overlay"></div>
    <div class="new-booking" hidden>
      <div id="popup" class="popup details">
        <form action="" method="post" autocomplete="off">
          <div class="detail detail-1">
            <h3>Receiver details</h3>
            <div class="receiver-name">
              <input type="text" id="bk-receiver" placeholder="🤵 Receiver name"  name="rec-name"/>
            </div>
            <div class="phone">
              <input
                type="text"
                id="contact"
                placeholder="📞 phone number" name="rec-phone"
              />
            </div>
            <div class="infos">
              <input
                type="text"
                id="home-addr"
                placeholder="📧 Home address" name="rec-addr"
              />
            <div class="mail">
              <input type="email" id="email" placeholder="📧 Email address (*Optional)" name="rec-email" />
            </div>
            </div>
          </div>
          <div
          class="detail detail-2"
          style="border-left: 2px solid #afafaf; padding-left: 20px"
          >
            <h3>Product details</h3>
            <div class="receiver-name nest-input">
              <input type="text" id="pdt-name" placeholder="Product Name" name="pdt-name" />
              <select name="selection" id="selection" tabindex="0">
                <option>Type</option>
                <option value="standard">Standard</option>
                <option value="priority">Priority</option>
                <option value="economy">Economy</option>
                <option value="heavyweight">Heavyweight</option>
                <option value="fragile">Fragile</option>
              </select>
            </div>
            <div>
              <input type="text" id="weight" placeholder="Weight (*Optional)" name="pdt-wght" />
            </div>
            <div class="more-details">
              <textarea
                name="desc"
                id="desc"
                placeholder="Descriptions (*Optional)"
              ></textarea>
            </div>
            <button type="submit" name="book" class="book book-new">Book</button>
            <button type="submit" class="close close-new">close</button>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>

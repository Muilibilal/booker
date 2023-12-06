<?php

$editCusErrorMsg = '';
$deleteCusErrorMsg = '';

// Edit booking form
if (isset($_POST['update-booking'])) {
   $receiverName = $_POST['rec-name'];
   $transID = $_POST['id'];
   $receiverPhone = $_POST['rec-phone'];
   $receiverAddr = $_POST['rec-addr'];
   $receiverEmail = $_POST['rec-email'];
   $productName = $_POST['pdt-name'];
   $selection = $_POST['selection'];
   $productWght = $_POST['pdt-wght'];
   $productDesc = $_POST['desc'];
   
   if (strlen(trim($receiverName)) === 0 || strlen(trim($receiverPhone)) === 0 || strlen(trim($receiverAddr)) === 0 || strlen(trim($productName)) === 0 || strlen(trim($selection)) === 0) {
   $addCusErrorMsg = 'One or more of your input is invalid';
   
   // Stop submission if one or more input is invalid
   goto end;
   }
   $addCusErrorMsg = '';
   $lastEdit = date("Y-m-d H:i:s");
   $editCusErrorMsg = '';

   $query = "UPDATE `bookings` SET `receiver` = '$receiverName', `product` = '$productName', `status` = 'Pending', `phone` = '$receiverPhone', `address` = '$receiverAddr', `type` = '$selection', `weight` = '$productWght', `email` = '$receiverEmail', `info` = '$productDesc', `last-edit` = '$lastEdit' WHERE `bookings`.`transactionId` = '$transID'";

   $result = mysqli_query($conn, $query);

   if ($result) {
      $editCusErrorMsg = '';
      header("Location:$_SERVER[PHP_SELF]");
   } else {
      $editCusErrorMsg = 'Unable to edit booking details';
   }
}

// Delete booking form
if (isset($_POST['delete-booking'])) {
   $transID = $_POST['delete-id'];

   $deleteCusErrorMsg = '';

   $query = "DELETE FROM `bookings` WHERE `bookings`.`transactionId` = '$transID'";

   $result = mysqli_query($conn, $query);

   if ($result) {
      $deleteCusErrorMsg = '';
      header("Location:$_SERVER[PHP_SELF]");
   } else {
      $deleteCusErrorMsg = 'Unable to delete booking';
   }
}

// Send payment confirmation
if (isset($_POST['confirm-payment'])) {
   $request = $_POST['payment-id'];

   $editCusErrorMsg = '';

   $sendReqest = "SELECT `payment_requests` from `bookings` WHERE `transactionId` = '$request'";
   $allResult = mysqli_query($conn, $sendReqest);
   $all = mysqli_fetch_all($allResult, 1);
   print_r($all);

   if ($all[0]['payment_requests'] === "confirm-payment") {
      echo "requests can only be sent once";
   }else{
   $query = "UPDATE `bookings` SET `payment_requests` = 'confirm-payment', `status` = 'in-progress'  WHERE `bookings`.`transactionId` = '$request'";

   $result = mysqli_query($conn, $query);

   if ($result) {
      $editCusErrorMsg = 'payment request successful';
      header("Location:$_SERVER[PHP_SELF]");
   } else {
      $editCusErrorMsg = 'Unable to make payment request';
   }
   }
}


// Approve payment request
if (isset($_POST['approve-payment'])) {
   $request = $_POST['payment-id'];

   $editCusErrorMsg = '';

   $updatePayment = "UPDATE `bookings` SET `status` = 'Successful', `payment_requests` = 'confirmed' WHERE `bookings`.`transactionId` = '$request'";
   $result = mysqli_query($conn, $updatePayment);

   
   if ($result) {
      $editCusErrorMsg = '';
      header("Location:$_SERVER[PHP_SELF]");
   } else {
      $editCusErrorMsg = 'Unable to approve booking payment';
   }
}

// Reject payment request
if (isset($_POST['reject-payment'])) {
   $request = $_POST['payment-id'];

   $editCusErrorMsg = '';

   $updatePayment = "UPDATE `bookings` SET `status` = 'Failed', `payment_requests` = 'confirmed' WHERE `bookings`.`transactionId` = '$request'";
   $result = mysqli_query($conn, $updatePayment);

   
   if ($result) {
      $editCusErrorMsg = '';
      header("Location:$_SERVER[PHP_SELF]");
   } else {
      $editCusErrorMsg = 'Unable to approve booking payment';
   }
}

end:
?>
<?php
// Create connection
$conn = new mysqli('localhost', 'root', '', 'booker');

if (mysqli_connect_errno()) {
   echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

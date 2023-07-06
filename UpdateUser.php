<?php
include "CheckValidUser.php";
include "DBConn.php";
global $conn;
$userID = $_SESSION['userID'];
//check the role is  purchase manager or supplier
$role = "";

if (isset($_SESSION['userID'])) {
  $userID = $_SESSION['userID'];
  if (str_starts_with($userID, 'p')) {
    $role = "Purchase Manager";
  } else if (str_starts_with($userID, 's')) {
    $role = "Supplier";
  }
}

if ($role == "Purchase Manager") {
  // Check if the required values are set
  if (isset($_POST['contactNumber'], $_POST['warehouseAddress'])) {
    // Retrieve the updated values from the AJAX request
    $contactNumber = $_POST['contactNumber'];
    $warehouseAddress = trim($_POST['warehouseAddress']);

    // Update the data in the database
    $sql = "UPDATE purchasemanager SET contactNumber = '$contactNumber', warehouseAddress = '$warehouseAddress' WHERE purchaseManagerID = '$userID'";

    if (mysqli_query($conn, $sql)) {
      echo "Update successful";
    } else {
      echo "Error updating data";
    }
  } else {
    echo "Missing required data";
  }
} // supplier role
else if ($role == "Supplier") {
  // Check if the required values are set
  if (isset($_POST['contactNumber'], $_POST['address'])) {
    // Retrieve the updated values from the AJAX request
    $contactNumber = $_POST['contactNumber'];
    $address = trim($_POST['address']);

    // Update the data in the database
    $sql = "UPDATE supplier SET contactNumber = '$contactNumber', address = '$address' WHERE supplierID = '$userID'";

    if (mysqli_query($conn, $sql)) {
      echo "Update successful";
    } else {
      echo "Error updating data";
    }
  } else {
    echo "Missing required data";
  }
}

mysqli_close($conn);
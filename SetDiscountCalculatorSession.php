<?php
include 'CheckValidUser.php';

if (isset($_POST['discountRate']) && isset($_POST['newOrderAmount'])) {
    $_SESSION['discountRate'] = $_POST['discountRate'];
    $_SESSION['newOrderAmount'] = $_POST['newOrderAmount'];
    echo $_SESSION['discountRate'];
    echo $_SESSION['newOrderAmount'];
}
?>

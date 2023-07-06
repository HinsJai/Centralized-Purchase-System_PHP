<?php
session_start();
# Check if the user has logged in
if (!isset($_SESSION['userID'])) {
    unset($_SESSION);
    echo "<script>alert('You do not login!');</script>";
    header("Location: index.php");
    exit();
}
?>
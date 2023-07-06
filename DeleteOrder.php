<?php
include 'CheckValidUser.php';
include 'DBConn.php';
global $conn;
$userID = "";
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
}

// Check if the order ID is set in the POST data
if (isset($_POST['orderID'])) {
    $orderID = $_POST['orderID'];

    // Perform the deletion and stock quantity update

    // Example code to delete the order and update the stock quantity
    $sqlDeleteOrdersItem = "DELETE FROM ordersitem WHERE orderID = $orderID";
    $sqlDeleteOrder = "DELETE FROM orders WHERE orderID = $orderID AND purchaseManagerID = '$userID'";
    $sqlUpdateStock = "UPDATE item
                       JOIN (
                            SELECT oi.itemID, SUM(oi.orderQty) AS totalOrderQty
                            FROM ordersitem oi
                            WHERE oi.orderID = $orderID
                            GROUP BY oi.itemID
                        ) AS totals ON item.itemID = totals.itemID
                        SET item.stockItemQty = item.stockItemQty + totals.totalOrderQty;";

    // Execute the SQL queries
    mysqli_query($conn, $sqlUpdateStock);
    mysqli_query($conn, $sqlDeleteOrdersItem);
    mysqli_query($conn, $sqlDeleteOrder);

    // Check if the deletion was successful
    if (mysqli_affected_rows($conn) > 0) {
        echo "success";
    } else {
        // Deletion or update failed
        echo "error";
    }
} else {
    // Order ID not found in the POST data
    echo "error";
}

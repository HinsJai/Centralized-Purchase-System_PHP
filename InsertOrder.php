<?php
// Check if the required POST variables are set
if (isset($_POST['itemList'], $_POST['orderID'], $_POST['purchaseManagerID'], $_POST['deliveryAddress'], $_POST['deliveryDate'])) {

    include 'CheckValidUser.php';
    include 'DBConn.php';
    global $conn;


    // Get the order data from the request
    $orderID = $_POST['orderID'];
    $purchaseManagerID = $_POST['purchaseManagerID'];
    $deliveryAddress = $_POST['deliveryAddress'];
    $deliveryDate = $_POST['deliveryDate'];
    $itemList = json_decode($_POST['itemList'], true);


    // Prepare the SQL statement to insert the order
    $sql = "INSERT INTO orders (orderID, purchaseManagerID, orderDateTime, deliveryAddress, deliveryDate)
        VALUES ('$orderID', '$purchaseManagerID', CURRENT_TIMESTAMP(), '$deliveryAddress', '$deliveryDate')";

    // Execute the order insert query
    if ($conn->query($sql) === TRUE) {
        // Prepare the SQL statement to insert the items
        $query = "INSERT INTO ordersitem (orderID, itemID, orderQty, itemPrice) VALUES ";

        foreach ($itemList as $item) {
            $itemID = $item['itemID'];
            $orderQty = $item['qty'];
            $itemPrice = $item['price'];

            // Append the values for each item to the query
            $query .= "('$orderID', '$itemID', '$orderQty', '$itemPrice'),";

            // Decrease the stockItemQty in the item table
            $updateQuery = "UPDATE item SET stockItemQty = stockItemQty - $orderQty WHERE itemID = '$itemID'";
            $conn->query($updateQuery);
        }

        // Remove the trailing comma from the query
        $query = rtrim($query, ",");

        // Execute the item insert query
        if ($conn->query($query) === TRUE) {
            echo "Order and items inserted successfully";
        } else {
            echo "Error inserting items: " . $conn->error;
        }
    } else {
        echo "Error inserting order: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Required data not provided";
}
?>

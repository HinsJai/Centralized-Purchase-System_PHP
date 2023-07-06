<?php
$pageTitle = 'Order Record';
include './includes/header.php';
include './GetDiscount.php';
$userID = $_SESSION['userID'];
global $conn;
global $userID;
$orderID = 0;
$purchaseManagerID = "";
$orderDateTime = "";
$deliveryAddress = "";
$deliveryDate = "";

if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];
    $_SESSION['orderID'] = $orderID;
}
// get Delivery Details

$sql = "SELECT DISTINCT o.orderID, o.purchaseManagerID, s.supplierID, s.companyName, 
        s.contactName, s.contactNumber, o.orderDateTime, o.deliveryAddress, o.deliveryDate, 
        i.itemID, i.ImageFile, i.itemName, oi.orderQty, oi.itemPrice 
        FROM orders o 
        INNER JOIN ordersitem oi ON o.orderID = oi.orderID 
        INNER JOIN item i ON oi.itemID = i.itemID 
        INNER JOIN supplier s ON i.supplierID = s.supplierID 
        WHERE o.orderID = {$_SESSION['orderID']} AND purchaseManagerID = '$userID'";

global $sql;

$sortBy = $_GET['sortBy'] ?? "itemID";
$sortOrder = $_GET['sortOrder'] ?? "ASC";
$sortOrder == "DESC" ? $sort = "ASC" : $sort = "DESC";
global $sort;

if (isset($_GET['sortBy']) && isset($_GET['sortOrder'])) {
    $sortBy = $_GET['sortBy'];
    $sortOrder = $_GET['sortOrder'];
    $sql = $sql . " ORDER BY $sortBy $sortOrder";
} else {
    $sql = $sql . " ORDER BY itemID ASC";
}


global $originalTotalAmount;
global $newTotalAmount;
global $discountRate;

$result = mysqli_query($conn, $sql);


if ($result->num_rows > 0) {
    $originalTotalAmount = 0;
    while ($rc = $result->fetch_assoc()) {
        extract($rc);
        $originalTotalAmount += $orderQty * $itemPrice;
    }
    //get discount amount
    $data = getDiscount($originalTotalAmount);
    $newTotalAmount = $data['NewOrderAmount'];
    $discountRate = $data['DiscountRate'] * 100;

    // Calculate two days before delivery
    $currentDate = date('Y-m-d');
    $twoDaysBeforeDelivery = date('Y-m-d', strtotime("-2 days", strtotime($deliveryDate)));
    // Check if the current date is within the valid deletion period
    $currentDate < $twoDaysBeforeDelivery ? $deleteBtn = "" : $deleteBtn = "disabled";


    mysqli_free_result($result);
} else {
    echo "<h1 class='text-center text-danger'>No Order Record</h1>";
}

?>
<div class="py-3 ml-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="car">
                    <div class="card-header fs-3">
                        Order Details
                        <a href="OrderRecord.php" class="btn btn-warning fw-bold float-end"><i class="fa fa-reply"></i>
                            Back</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <h4>Delivery Details</h4>
                                <hr class="bg-dark" style="border-width: 1px;  border-style: inset;">
                                <label for="" class="fs-5 fw-bold">Order ID</label>
                                <div class="border p1" id="orderID">
                                    <?php echo "$orderID"; ?>
                                </div>
                                <label for="" class="fs-5 mt-4 fw-bold">Purchase Manager ID</label>
                                <div class="border p1">
                                    <?php
                                    echo "$purchaseManagerID";
                                    ?>
                                </div>
                                <label for="" class="fs-5 mt-4 fw-bold">Create Order Date Time</label>
                                <div class="border p1">
                                    <?php
                                    echo "$orderDateTime";
                                    ?>
                                </div>
                                <label for="" class="fs-5 mt-4 fw-bold">Delivery Date</label>
                                <div class="border p1">
                                    <?php
                                    echo "$deliveryDate";
                                    ?>
                                </div>
                                <label for="" class="fs-5 mt-4 fw-bold">Delivery Address</label>
                                <div class="p1">
                                    <textarea cols="24" rows="5" class="bg-gray-200 form-control" readonly><?php echo "$deliveryAddress"; ?>
                                        </textarea>
                                </div>
                                <h4 class="mt-4">Order Amount</h4>
                                <hr class="bg-dark" style="border-width: 1px;  border-style: inset;">
                                <label for="" class="fs-5 mt-2 fw-bold text-danger">Discount Rate</label>
                                <div class="border p1 fs-5">
                                    <?php
                                    echo "Discount Rate = $discountRate%";
                                    ?>
                                </div>
                                <label for="" class="fs-5 mt-4 fw-bold text-danger"> Original Total Amount</label>
                                <div class="border p1 fs-5">
                                    <?php
                                    echo "\$HKD = $originalTotalAmount";
                                    ?>
                                </div>
                                <label for="" class="fs-5 mt-4 fw-bold text-danger">New Total Amount</label>
                                <div class="border p1 fs-5">
                                    <?php
                                    echo "\$HKD = $newTotalAmount";
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Item Details</h4>
                                <hr class="bg-dark" style="border-width: 1px;  border-style: inset;">
                                <table class="table table-striped table-light">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-center align-middle p-2"><a
                                                    href="<?php echo "?sortBy=itemID&sortOrder=$sort" ?>">Item ID
                                                    <i class="material-icons">sort</i>
                                                </a>
                                            </th>
                                            <th scope="col" class="text-center align-middle p-2"><a
                                                    href="<?php echo "?sortBy=itemName&sortOrder=$sort" ?>">Item Name
                                                    <i class="material-icons">sort</i>
                                                </a>
                                            </th>
                                            <th scope="col" class="text-center align-middle p-2"><a
                                                    href="<?php echo "?sortBy=supplierID&sortOrder=$sort" ?>">Supplier
                                                    ID
                                                    <i class="material-icons">sort</i>
                                                </a>
                                            </th>
                                            <th scope="col" class="text-center align-middle p-2"><a
                                                    href="<?php echo "?sortBy=companyName&sortOrder=$sort" ?>">
                                                    Supplier Company Name
                                                    <i class="material-icons">sort</i>
                                                </a>
                                            </th>
                                            <th scope="col" class="text-center align-middle p-2"><a
                                                    href="<?php echo "?sortBy=contactName&sortOrder=$sort" ?>">
                                                    Supplier Contact Name
                                                    <i class="material-icons">sort</i>
                                                </a>
                                            </th>
                                            <th scope="col" class="text-center align-middle p-2"><a
                                                    href="<?php echo "?sortBy=contactNumber&sortOrder=$sort" ?>">
                                                    Supplier Contact
                                                    <i class="material-icons">sort</i>
                                                </a>
                                            </th>
                                            <th scope="col" class="text-center align-middle p-2"><a
                                                    href="<?php echo "?sortBy=itemPrice&sortOrder=$sort" ?>">
                                                    Item Price
                                                    <i class="material-icons">sort</i>
                                                </a>
                                            </th>
                                            <th scope="col" class="text-center align-middle p-2"><a
                                                    href="<?php echo "?sortBy=orderQty&sortOrder=$sort" ?>">Order Qty
                                                    <i class="material-icons">sort</i>
                                                </a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = mysqli_query($conn, $sql);
                                        if ($result->num_rows > 0) {
                                            while ($rc = $result->fetch_assoc()) {
                                                extract($rc);
                                                echo "<tr>";
                                                echo "<td class='table-info text-center align-middle'>" . $itemID . "</td>";
                                                echo "<td class='table-info'><img src='./images/$ImageFile' alt='$itemName' class='img-fluid img-thumbnail item_img' width='50' height='50' ><span class='item_name ml-1'>$itemName</span> . </td>";
                                                echo "<td class='table-info text-center align-middle'>" . $supplierID . "</td>";
                                                echo "<td class='table-info text-center align-middle'>" . $companyName . "</td>";
                                                echo "<td class='table-info text-center align-middle'>" . $contactName . "</td>";
                                                echo "<td class='table-info text-center align-middle'>" . $contactNumber . "</td>";
                                                echo "<td class='table-info text-center align-middle'>" . $itemPrice . "</td>";
                                                echo "<td class='table-info text-center align-middle'>" . $orderQty . "</td>";
                                                echo "</tr>";
                                            }
                                        }
                                        mysqli_free_result($result);
                                        mysqli_close($conn);
                                        ?>
                                    </tbody>
                                </table>
                                <a href="" class="btn btn-lg btn-danger fw-bold float-end mt-3 <?php echo $deleteBtn ?>"
                                    id="delete_order"><i class="fa-solid fa-trash"></i> Delete Order</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include './includes/footer.php'; ?>
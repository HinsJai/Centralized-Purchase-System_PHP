<?php
$pageTitle = 'Create Order';
include './includes/header.php';
include './GetDiscount.php';
global $conn;

// Retrieve the selected items from the session
$selectedItems = $_SESSION['selectedItems'] ?? array();

// Set session timeout to 20 minutes
$sessionTimeout = 20 * 60;

// Check if the session is already expired
if (isset($_SESSION['lastActivity']) && (time() - $_SESSION['lastActivity'] > $sessionTimeout)) {

    // Clear all table rows
    $_SESSION['selectedItems'] = array();
}

// Update the last activity timestamp
$_SESSION['lastActivity'] = time();

$itemSql = "SELECT Item.*, Supplier.companyName
        FROM Item
        INNER JOIN Supplier ON Item.supplierID = Supplier.supplierID";
$itemResult = mysqli_query($conn, $itemSql);

// Get the session userID
$userID = $_SESSION['userID'];

// Retrieve the purchase manager name and warehouse address
$sql = "SELECT managerName, warehouseAddress 
        FROM purchasemanager
        WHERE purchaseManagerID = '$userID'";
$result = mysqli_query($conn, $sql);
$rc = mysqli_fetch_assoc($result);

mysqli_free_result($result);


// Retrieve the values from the query result
$managerName = $rc['managerName'];
$warehouseAddress = $rc['warehouseAddress'];

// Retrieve the maximum order ID from the orders table
$maxOrderIDSql = "SELECT MAX(orderID) AS maxOrderID FROM orders";
$maxOrderIDResult = mysqli_query($conn, $maxOrderIDSql);
$maxOrderIDRow = mysqli_fetch_assoc($maxOrderIDResult);
$maxOrderID = $maxOrderIDRow['maxOrderID'];

mysqli_free_result($maxOrderIDResult);
mysqli_close($conn);

// If there are no orders in the table, default to order ID 1
$orderID = $maxOrderID ? $maxOrderID + 1 : 1;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the selected item data from AJAX request
    $itemList = $_POST['itemList'] ?? array();

    // Store the selected item in the session
    $_SESSION['selectedItems'] = $itemList;

    // Check if an item ID was sent for deletion
    $deleteItemID = $_POST['deleteItemID'] ?? null;
    if ($deleteItemID) {
        // Find the item with the matching itemID in the itemList
        $index = array_search($deleteItemID, array_column($itemList, 'itemID'));
        if ($index !== false) {
            // Remove the item from the itemList
            $deletedItem = $itemList[$index];
            array_splice($itemList, $index, 1);

            // Recalculate newOrderAmount based on the updated itemList
            $_SESSION['newOrderAmount'] = calculateTotal($itemList);
            $totalOrderAmount = $_SESSION['newOrderAmount'];

            $data = getDiscount($totalOrderAmount);
            $totalOrderAmount = $data['NewOrderAmount'];
            $discountRate = $data['DiscountRate'];


//            $url = "http://127.0.0.1:8080/api/discountCalculator?TotalOrderAmount=$totalOrderAmount";
//
//            // Send a request to the discount calculator API
//            $response = file_get_contents($url);
//
//            // Check if the response is successful
//            if ($response !== false) {
//                // Decode the JSON response
//                $data = json_decode($response, true);
//
//                // Get the discount rate from the response
//                $discountRate = $data['DiscountRate'];
//                $_SESSION['discountRate'] = $discountRate;
//            } else {
//                // Handle the case when the API request fails
//                echo "Failed to retrieve discount rate from API";
//            }
        }
    }

}

if (!isset($_SESSION['newOrderAmount'])) {
    $_SESSION['newOrderAmount'] = 0;
}

$totalAmount = $_SESSION['newOrderAmount'];
global $totalAmount;

function calculateTotal($itemList)
{
    $total = 0;
    foreach ($itemList as $item) {
        $total += $item['price'] * $item['qty'];
    }
    return $total;
}

?>

<title>Create Order</title>

<div class="container-fluid">
    <div class="row">
        <div class="col-10 offset-1">
            <div class="card">
                <div class="card-body">
                    <div class="row-auto">
                        <h1 class="text-center">Create Order</h1>
                        <button class="btn btn-info  position-relative" style="bottom:50px; left: 80px"
                                onclick="location.href='ProductList.php';">
                            <i class="material-icons"">add_shopping_cart</i>
                            Product List
                        </button>
                    </div>
                    <form action="CreateOrder.php" method="post" class="form-label position-relative"
                          style="left: 70px;">
                        <div id="remainingTime" class="position-relative font-weight-bold fs-6"
                             style="left: 70%; bottom: 100px;"></div>
                        <div class="datetime">
                            <label for="orderDateTime" class="date-time-title">Order Date & Time:</label>
                            <div class="time"></div>
                            <div class="date"></div>
                        </div>

                        <fieldset class="form-group">
                            <legend class="col-form-label fs-3">Order information</legend>
                            <div class="col-auto" class="col-form-label">
                                <label for="orderID col-form-label">Order ID:</label>
                                <input type="text" class="form-control" id="orderID" name="orderID"
                                       value="<?php echo $orderID ?>"
                                       readonly/>
                            </div>
                            <div class="col-auto mt-3">
                                <label for="purchaseManagerID" class="col-form-label">Purchase Manager ID:</label>
                                <input type="text" class="form-control" id="purchaseManagerID" name="purchaseManagerID"
                                       readonly
                                       value="<?php echo $userID ?>"/>
                            </div>
                            <div class="col-auto mt-3">
                                <label for="managerName" class="col-form-label">Manager Name:</label>
                                <input type="text" class="form-control" id="managerName" name="managerName" readonly
                                       value="<?php echo $managerName ?>" required/>
                            </div>
                            <div class="col-auto mt-3">
                                <label for="deliveryDate" class="col-form-label">Delivery Date:</label>
                                <input type="date" class="form-control" id="deliveryDate" name="deliveryDate" value=""
                                       min="<?php echo date('Y-m-d'); ?>" required/>
                            </div>

                            <div class="col-auto mt-3">
                                <label for="deliveryAddress" class="col-form-label">Delivery Address:</label>
                                <textarea id="deliveryAddress" class="form-control" name="deliveryAddress" rows="4"
                                          cols="50"
                                          maxlength="255" title="max length = 255"
                                          placeholder="e.g. Unit 4015/Fl. Silvercord Tower 230 Canton Road"
                                          required><?php echo $warehouseAddress ?>
                                </textarea>
                            </div>
                        </fieldset>
                        <div class="position-relative position-relative" style="left: 16px">
                            <div class="col-form-label fs-3">Order List</div>
                            <table id="table" class="table table-dark table-striped table-hover table-striped"
                                   style="width: 85%;">
                                <tr>
                                    <th class="text-center align-middle">Item ID</th>
                                    <th class="text-center align-middle">Item Name</th>
                                    <th class="text-center align-middle">Item Image</th>
                                    <th class="text-center align-middle">Unit Price ($HKD)</th>
                                    <th class="text-center align-middle">Stock Qty</th>
                                    <th class="text-center align-middle">Selected Qty</th>
                                    <th class="text-center align-middle">Item Amount</th>
                                    <th class="text-center align-middle">Delete Item</th>
                                </tr>
                                <?php foreach ($selectedItems as $item) : ?>
                                    <tr>
                                        <td class="table-info text-center align-middle"><?php echo $item['itemID']; ?></td>
                                        <td class="table-info text-center align-middle"><?php echo $item['itemName']; ?></td>
                                        <td class="table-info text-center align-middle"><img
                                                src="<?php echo $item['itemImg']; ?>"
                                                alt="item image" width="70" height="70"
                                                class="img-fluid img-thumbnail">
                                        <td class="table-info text-center align-middle"><?php echo $item['price']; ?></td>
                                        <td class="table-info text-center align-middle"><?php echo $item['stockQty']; ?></td>
                                        <td class="table-info text-center align-middle">
                                            <input type="number" class="form-control position-relative selected_Qty"
                                                   id="selected_Qty"
                                                   style='max-width: 100px; left: 25px'
                                                   min="1"
                                                   value="<?php echo $item['qty']; ?>"
                                                   onchange="updateQty(this,event);">
                                        </td>
                                        <td class="table-info text-center align-middle"><?php echo $item['price'] * $item['qty']; ?></td>
                                        <td class="table-info text-center align-middle position-relative">
                                            <button class="btn btn-danger position-relative delete_btn"
                                                    style="top: 8px;"
                                                    onclick="deleteItem(this,event); return false;">Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                            <div id="discount_rate" class="fs-4 position-relative font-weight-bold text-warning"
                                 style="top: 10px">
                            </div>
                            <div id="total_amount" class="fs-4 position-relative font-weight-bold  text-success"
                                 style="top: 10px">
                            </div>
                            <div id="new_total_amount" class="fs-4 position-relative font-weight-bold text-danger"
                                 style="top: 10px">
                            </div>

                            <div class="row position-relative" style="top: 30px;">
                                <div class="col">
                                    <input type="submit" class="btn btn-lg btn-primary" id="submitOrder"
                                           value="Submit Order"/>
                                </div>
                                <div class="col">
                                    <input type="reset" class="btn btn-lg btn-secondary position-relative" id="reset"
                                           value="Cancel" style="left: 40%"/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Timer variables
    var timer;
    var remainingSeconds = <?php echo $sessionTimeout; ?>;

    // Start the countdown timer
    function startTimer() {
        timer = setInterval(updateTimer, 1000);
    }

    // Update the timer display every second
    function updateTimer() {
        var minutes = Math.floor(remainingSeconds / 60);
        var seconds = remainingSeconds % 60;

        // Format the remaining time
        var remainingTime = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');

        // Display the remaining time
        document.getElementById('remainingTime').textContent = 'Create Order Remaining Time: ' + remainingTime;

        // Check if the session has expired
        if (remainingSeconds <= 0) {
            clearInterval(timer);
            clearLocalStorage();
            window.location.href = 'ProductList.php';
        }

        // Decrease the remaining time
        remainingSeconds--;
    }

    function clearLocalStorage() {
        localStorage.clear();
        itemList=[];
    }

    // Call the startTimer function to initiate the countdown
    startTimer();
</script>

<?php include './includes/footer.php'; ?>

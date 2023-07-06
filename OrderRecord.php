<?php
$pageTitle = 'Order Record';
include './includes/header.php';
$userID = $_SESSION['userID'];
global $conn;
global $userID;

if (isset($_GET['searchValue'])) {
    $searchValue = $_GET['searchValue'];
}

$isset = isset($_GET['searchValue']) ? "visible" : "invisible";

global $isset;
global $searchValue;


$sortBy = $_GET['sortBy'] ?? "orderID";
$sortOrder = $_GET['sortOrder'] ?? "ASC";

$sortOrder == "DESC" ? $sort = "ASC" : $sort = "DESC";
global $sort;

$orderSql="";

if (isset($_GET['sortBy']) && isset($_GET['sortOrder'])) {
    $sortBy = $_GET['sortBy'];
    $sortOrder = $_GET['sortOrder'];
    $orderSql = "SELECT * FROM orders WHERE purchaseManagerID = '$userID' ORDER BY $sortBy $sortOrder";
} else {
    $orderSql = "SELECT * FROM orders WHERE purchaseManagerID = '$userID' ORDER BY orderID ASC";
}

//$orderResult = mysqli_query($conn, $orderSql);

?>
    <style>
        hr {
            display: block;
            margin: 0.5em 5% 0.9em 5%;
            border-style: inset;
            border-color: black;
            border-width: 1px;
        }
    </style>

    <h1 class="h1 text-center pt-4">Order Record</h1>
    <hr>
    <span class="position-relative" style="left: 70px">
       <a href="OrderRecord.php" class="btn btn-warning fw-bold <?php echo $isset ?>"><i class="fa fa-reply"></i> Black</a>
    </span>

    <div class="col position-relative float-end" style=" width: 200px">
        <select name="select_box" class="form-select p-0" id="select_box" onchange="orderSearch()">
            <option class="p-1">Search Order</option>
            <?php
            $orderIDSql = "SELECT orderID FROM orders WHERE purchaseManagerID = '$userID'";
            $orderIDResult = mysqli_query($conn, $orderIDSql);

            while ($rc = mysqli_fetch_assoc($orderIDResult)) {
                extract($rc);
                echo '<option class= "fs-5 p-1" value="' . $orderID . '">' . "Order ID: " . $orderID . '</option>';
            }
            ?>
        </select>
    </div>

    <div class="py-3 ml-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" class="text-center align-middle"><a
                                    href="<?php echo "?sortBy=orderID&sortOrder=$sort" ?>">Order ID<i
                                        class="material-icons pl-2">sort</i></a>
                            <th scope="col">Purchase Manager ID</th>
                            <th scope="col" class="text-center align-middle"><a
                                    href="<?php echo "?sortBy=orderDateTime&sortOrder=$sort" ?>">Create Order Date
                                    Time<i class="material-icons pl-2">sort</i></a>
                            <th scope="col">Delivery Address</th>
                            <th scope="col" class="text-center align-middle"><a
                                    href="<?php echo "?sortBy=deliveryDate&sortOrder=$sort" ?>">Delivery Date<i
                                        class="material-icons pl-2">sort</i></a>
                            <th scope="col">Order Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (isset($_GET['searchValue'])) {
                            $orderID = $_GET['searchValue'];
                            $searchSql = "SELECT * FROM orders WHERE orderID = '$orderID' AND purchaseManagerID = '$userID'";
                            $result = mysqli_query($conn, $searchSql);

                            if ($result->num_rows > 0) {
                                while ($rc = $result->fetch_assoc()) {
                                    extract($rc);
                                    echo "<tr>";
                                    echo "<td class='text-center align-middle'>" . $orderID . "</td>";
                                    echo "<td class='text-center align-middle'>" . $purchaseManagerID . "</td>";
                                    echo "<td class='text-center align-middle'>" . $orderDateTime . "</td>";
                                    echo "<td class='text-center align-middle'><textarea class='bg-gradient-light form-control' type='text' cols='30' rows='3' readonly>$deliveryAddress</textarea></td>";
                                    echo "<td class='text-center align-middle'>" . $deliveryDate . "</td>";
                                    echo "<td class='text-center align-middle'><a href='OrderDetails.php?orderID=$orderID' class='btn btn-primary'>View Details</a></td>";
                                    echo "</tr>";
                                }
                                mysqli_free_result($result);

                            }
                        } else {
                        $result = mysqli_query($conn, $orderSql);
                            if ($result->num_rows > 0) {
                                while ($rc = $result->fetch_assoc()) {
                                    extract($rc);
                                    echo "<tr>";
                                    echo "<td class='text-center align-middle'>" . $orderID . "</td>";
                                    echo "<td class='text-center align-middle'>" . $purchaseManagerID . "</td>";
                                    echo "<td class='text-center align-middle'>" . $orderDateTime . "</td>";
                                    echo "<td class='text-center align-middle'><textarea class='bg-gradient-light' type='text' cols='30' rows='3' readonly>$deliveryAddress</textarea></td>";
                                    echo "<td class='text-center align-middle'>" . $deliveryDate . "</td>";
                                    echo "<td class='text-center align-middle'><a href='OrderDetails.php?orderID=$orderID' class='btn btn-primary'>View Details</a></td>";
                                    echo "</tr>";
                                }
                                mysqli_free_result($result);

                            } else {
                                echo "<h1 class='text-center text-danger'>No Order Record</h1>";
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                    mysqli_close($conn);
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php include './includes/footer.php'; ?>
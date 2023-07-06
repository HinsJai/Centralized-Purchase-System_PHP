<?php

$pageTitle = 'Product List';
include './includes/header.php';
echo "<link rel='stylesheet' href='style/ProductList.css'/>";
global $conn;

$sortBy = $_GET['sortBy'] ?? "itemID";
$sortOrder = $_GET['sortOrder'] ?? "ASC";

if (isset($_GET['searchBy']) && isset($_GET['searchValue'])) {
    $searchBy = $_GET['searchBy'];
    $searchValue = $_GET['searchValue'];
    $itemSql = "SELECT Item.*, Supplier.companyName 
        FROM Item 
        INNER JOIN Supplier ON Item.supplierID = Supplier.supplierID
        WHERE $searchBy LIKE '%$searchValue%'";
} else {
    $itemSql = "SELECT Item.*, Supplier.companyName 
        FROM Item 
        INNER JOIN Supplier ON Item.supplierID = Supplier.supplierID
        ORDER BY $sortBy $sortOrder";
}

$itemResult = mysqli_query($conn, $itemSql);

global $newTotalAmount;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['itemList'])) {
    $itemList = $_POST['itemList'];
}

$sortOrder == "DESC" ? $sort = "ASC" : $sort = "DESC";

?>

<div class="container-fluid position-relative">
    <div class="row">
        <div class="col-md-12 position-relative" style="left: 6px">
            <div class="card">
                <div class="card-body">
                    <form id="productForm form-label" method="get" action="CreateOrder.php" class="position-relative"
                        style="bottom: 20px">
                        <h1 class="text-center">Product List</h1>
                        <hr>
                        <div class="input-group justify-content-end">
                            <span class="col  pl-5">
                                <button type="button" class="btn btn-primary btn-lg" id="createOrderBtn"
                                    onclick="placeOrder()">Create Order</button>
                            </span>
                            <select id="searching" class="form-select p-0 mr-4"
                                style="max-width: 200px; max-height: 39px">
                                <optgroup label="Supplier"></optgroup>
                                <option value="supplier_id">Supplier ID</option>
                                <option value="supplier_name">Supplier Name</option>
                                <optgroup label="Item"></optgroup>
                                <option value="item_id">Item ID</option>
                                <option value="item_name">Item Name</option>
                            </select>
                            <div class="form-outline">
                                <input type="search" id="search_bar" placeholder="Search" class="form-control" />
                            </div>
                            <button type="button" class="btn btn-primary position-relative" style="max-height: 38px;"
                                onclick="performSearch();">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <table class="table table-light pr-2 table-striped">
                            <tr>
                                <th scope="col" class="text-center align-middle"><a
                                        href="<?php echo "?sortBy=itemID&sortOrder=$sort" ?>">Item ID<i
                                            class="material-icons pl-2">sort</i></a>
                                </th>
                                <th scope="col" class="text-center align-middle"><a
                                        href="<?php echo "?sortBy=supplierID&sortOrder=$sort" ?>">Supplier ID<i
                                            class="material-icons pl-2">sort</i></a></th>
                                <th scope="col" class="text-center align-middle"><a
                                        href="<?php echo "?sortBy=companyName&sortOrder=$sort" ?>">Supplier Name<i
                                            class="material-icons pl-2">sort</i></a></th>
                                <th scope="col" class="text-center align-middle"><a
                                        href="<?php echo "?sortBy=itemName&sortOrder=$sort" ?>">Item Name<i
                                            class="material-icons pl-2">sort</i></a></th>
                                <th scope="col" class="text-center align-middle">Item Description</th>
                                <th scope="col" class="text-center align-middle"><a
                                        href="<?php echo "?sortBy=stockItemQty&sortOrder=$sort" ?>">Stock Qty<i
                                            class="material-icons pl-2">sort</i></a></th>
                                <th scope="col" class="text-center align-middle"><a
                                        href="<?php echo "?sortBy=price&sortOrder=$sort" ?>">Price<i
                                            class="material-icons pl-2">sort</i></a>
                                </th>
                                <th scope="col" class="text-center align-middle pl-2" style="max-width: 100px">Add
                                    Qty
                                </th>
                                <th scope="col">Select Item</th>
                            </tr>
                            <?php
                            if ($itemResult->num_rows > 0) {
                                while ($rc = $itemResult->fetch_assoc()) {
                                    extract($rc);
                                    $rowClass = $stockItemQty <= 0 ? 'bg-danger' : '';
                                    echo "<tr>";
                                    echo "<td class='table-info text-center align-middle $rowClass'>" . $itemID . "</td>";
                                    echo "<td class='table-info text-center align-middle $rowClass'>" . $supplierID . "</td>";
                                    echo "<td class='table-info text-center align-middle $rowClass'>" . $companyName . "</td>";
                                    echo "<td class='table-info text-center align-middle $rowClass'><img src='./images/$ImageFile' alt='$itemName' class='img-fluid img-thumbnail item_img' width='90' height='90' ><span class='item_name ml-2'>$itemName</span> . </td>";
                                    echo "<td class='table-info text-center align-middle $rowClass' style='max-width: 200px; word-wrap: break-word;'><textarea class='bg-gradient-light position-relative form-control' type='text' cols='27' rows='4' readonly>$itemDescription</textarea>" . "</td>";
                                    echo "<td class='table-info text-center align-middle $rowClass'>" . $stockItemQty . "</td>";
                                    echo "<td class='price table-info text-center align-middle text-danger font-weight-bold fs-4 $rowClass'>" . $price . "</td>";
                                    if ($stockItemQty > 0) {
                                        echo "<td class='table-info text-center align-middle $rowClass' style='max-width: 200px;'>
                                                  <input type='number' name='add_Qty' class='add_Qty form-control position-relative' min='0' max='$stockItemQty' style='max-width: 100px;'></td>";
                                        echo "<td class='table-info text-center align-middle $rowClass'><button type='button' class='btn btn-primary position-relative' style='right: 20px; top:6px;' onclick='addItemToArray(this)'>Add Item</button></td>";
                                    } else {

                                        echo "<td class='table-info text-center align-middle $rowClass' style='max-width: 200px;'>
                                                  <input type='number' name='add_Qty' class='add_Qty form-control position-relative'  style='max-width: 100px;' disabled></td>";
                                        echo "<td class='table-info text-center align-middle $rowClass'><button type='button' class='btn btn-primary position-relative' style='right: 20px; top:6px;' disabled>Add Item</button></td>";
                                    }
                                    echo "</tr>";
                                }
                            } else {
                                echo "Item not found";
                            }
                            mysqli_free_result($itemResult);
                            mysqli_close($conn);
                            ?>
                        </table>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include './includes/footer.php'; ?>
<?php
global $pageTitle;
include_once 'db.php';
$id = $_SESSION["userID"];
if (isset($_POST['delete'])) {
    $itemID = $_POST['itemID'];
    header("Location: item_list.php", true, 301);
    exit();
}

if (!isset($_GET['sort_type'])) {
    $sort_type = "itemID";
} else {
    $sort_type = $_GET['sort_type'];
}

if (!isset($_GET['sort_order'])) {
    $sort_order = "ASC";
} else {
    $sort_order = $_GET['sort_order'];
}
$items = get_all_items($id);
if (isset($_GET['type']) && isset($_GET['condition'])) {
    $items = array_filter($items, function ($item) {
        return strpos(strtoupper($item->{$_GET['type']}), strtoupper($_GET['condition'])) !== false;
    });
}
sort_items();

function sort_items()
{
    global $items;
    global $sort_order;
    usort($items, 'sort_by_key');
    if ($sort_order == 'DESC') {
        $items = array_reverse($items);
    }
}

function sort_by_key($item1, $item2)
{
    global $sort_type;
    $key1 = '';
    $key2 = '';
    switch ($sort_type) {
        case 'supplierID':
            $key1 = $item1->supplierID;
            $key2 = $item2->supplierID;
            break;
        case 'supplierName':
            $key1 = $item1->companyName;
            $key2 = $item2->companyName;
            break;
        case 'itemID':
            $key1 = $item1->itemID;
            $key2 = $item2->itemID;
            break;
        case 'itemName':
            $key1 = $item1->itemName;
            $key2 = $item2->itemName;
            break;
        case 'stockItemQty':
            $key1 = $item1->stockItemQty;
            $key2 = $item2->itemName;
            break;
        case 'price':
            $key1 = $item1->itemName;
            $key2 = $item2->itemName;
            break;
    }
    if ($key1 == $key2) {
        return 0;
    }
    return ($key1 < $key2) ? -1 : 1;
}

?>

<!DOCTYPE html>
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this item?");
    }
</script>
<html>

<head>
    <title>
        <?php echo $pageTitle; ?>
    </title>
    <link rel="stylesheet" href="item_list.css">
    <script src="https://kit.fontawesome.com/6b38d9ee41.js" crossorigin="anonymous"></script>
</head>

<body>
<div class="container">
    <h1>Item List</h1>

    <div class="item-list bg-light">
        <div class="form-container">
            <div class="button-container">
                <form method="post" id="insert_btn" action="insert_item.php">
                    <button type="submit" name="insert_item">Add New Item</button>
                </form>
                <form action="generate_report.php" id="report_btn" method="post" id="generate_report_btn">
                    <button type="submit" name="generate_report">Generate Report</button>
                </form>
            </div>

            <form class="search-form" method="get" action="">
                <label for="Search" class="search_by">Search by:&nbsp;</label>
                <select name="type">
                    <option value="itemID">Item ID</option>
                    <option value="itemName">Item Name</option>
                </select>
                <input type="text" name="condition">
                <button type="submit" name="search">Search</button>
            </form>
        </div>

        <?php if (count($items) < 1): ?>
            <div class="no-records">
                No relevant search records.
            </div>
        <?php else: ?>
            <?php include_once('show_item_details.php'); ?>
        <?php endif; ?>
    </div>
</div>
</body>

</html>
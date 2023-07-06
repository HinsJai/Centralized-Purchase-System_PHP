<?php
$items_details = generate_report($_SESSION['userID']);
$item_count = 0;
$total_qty = 0;
$total_sales = 0;
foreach ($items_details as $item) {
    $item_count++;
    $total_qty += $item['TOTALQUANTITY'];
    $total_sales += $item['TOTALSALESAMOUNT'];
}
?>

<div class="container">
    <h1>Sales Report</h1>
    <div class="item-list">
        <table>
            <tr>
                <th style="text-align: center;">Sold Item Count</th>
                <th style="text-align: center;">Total Sales Quantity</th>
                <th style="text-align: center;">Total Sales Amount</th>
            </tr>
            <tr>
                <td>
                    <?php echo $item_count; ?>
                </td>
                <td>
                    <?php echo $total_qty; ?>
                </td>
                <td>
                    <?php echo '$' . $total_sales; ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="container">
    <h2>Sold Item Details</h2>
    <div class="item-list">
        <table class="table table-striped bg-light">
            <tr>
                <th style="text-align: center;">Item ID</th>
                <th style="text-align: center;">Item Name</th>
                <th style="text-align: center;">Item Image</th>
                <th style="text-align: center;">Total Quantity</th>
                <th style="text-align: center;">Total Sales Amount</th>
            </tr>
            <?php foreach ($items_details as $item): ?>
                <tr>
                    <td>
                        <?php echo $item['ITEMID']; ?>
                    </td>
                    <td>
                        <?php echo $item['ITEMNAME']; ?>
                    </td>
                    <td>
                        <img src="<?php echo "Images/" . $item['ITEMIMAGE']; ?>" alt="<?php echo $item['ITEMIMAGE']; ?>"
                            height="100">
                    </td>
                    <td>
                        <?php echo $item['TOTALQUANTITY']; ?>
                    </td>
                    <td>
                        <?php echo '$' . $item['TOTALSALESAMOUNT']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<style>
    td {
        text-align: center;
    }
</style>
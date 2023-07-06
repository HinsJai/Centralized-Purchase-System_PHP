<table class="table table-striped">
    <tr>
        <th scope="col">Supplier ID</th>
        <th scope=" col">Supplier Name</th>
        <th scope="col">
            <a href="?sort_type=itemID&sort_order=<?php echo $sort_order == "ASC" ? 'DESC' : 'ASC' ?>">
                Item ID <i class="fa fa-sort"></i>
            </a>
        </th>
        <th scope="col">
            <a href=" ?sort_type=itemName&sort_order=<?php echo $sort_order == "ASC" ? 'DESC' : 'ASC' ?>">
                Item Name <i class="fa fa-sort"></i> </a>
        </th>
        <th scope="col">Item Image</th>
        <th scope="col">Item Description</th>
        <th scope="col">
            <a href="?sort_type=stockItemQty&sort_order=<?php echo $sort_order == "ASC" ? 'DESC' : 'ASC' ?> ">
                Stock Quantity <i class="fa fa-sort"></i>
            </a>
        </th>
        <th scope="col">
            <a href=" ?sort_type=price&sort_order=<?php echo $sort_order == "ASC" ? 'DESC' : 'ASC' ?>">
                Price <i class="fa fa-sort"></i>
            </a>
        </th>
        <th scope="col"></th>
        <th scope="col"></th>
    </tr>
    <?php foreach ($items as $item): ?>
    <tr>

        <td>
            <?php echo $item->supplierID; ?>
        </td>
        <td>
            <?php echo $item->companyName; ?>
        </td>
        <td>
            <?php echo $item->itemID; ?>
        </td>
        <td>
            <?php echo $item->itemName; ?>
        </td>
        <td><img src="<?php echo "images/" . $item->ImageFile; ?>" alt="<?php echo $item->itemName; ?>">
        </td>
        <td><textarea name=" itemDescription" cols="20" rows="5"
                readonly><?php echo $item->itemDescription; ?></textarea>
        </td>
        <td>
            <?php echo $item->stockItemQty; ?>
        </td>
        <td class="price">
            <?php echo '$' . $item->price; ?>
        </td>
        <td>
            <form method="post" action="edit_item.php">
                <input type="hidden" name="id" value="<?php echo $item->itemID;
                    $_SESSION['edit_itemID'] = $item->itemID ?>">
                <input type="hidden" name="image" value="<?php echo $item->ImageFile;
                    $_SESSION['edit_ImageFile'] = $item->ImageFile ?>">
                <input type="hidden" name="description" value="<?php echo $item->itemDescription;
                    $_SESSION['edit_itemDescription'] = $item->itemDescription ?>">
                <input type="hidden" name="price" value="<?php echo $item->price;
                    $_SESSION['edit_price'] = $item->price ?>">
                <input type="hidden" name="stock_qty" value="<?php echo $item->stockItemQty;
                    $_SESSION['edit_stockItemQty'] = $item->stockItemQty; ?>">
                <button type="submit" name="edit">Edit</button>
            </form>
        </td>
        <td>
            <form method="post" action="delete_item.php" onsubmit="return confirmDelete();">
                <input type="hidden" name="id" value="<?php echo $item->itemID; ?>">
                <button type="submit" style="background-color: red;">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach ?>
</table>
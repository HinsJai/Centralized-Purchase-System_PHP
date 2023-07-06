<?php
global $id;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $itemID = $_POST['itemID'];
    $supplierID = $_POST['supplierID'];
    $itemName = $_POST['itemName'];
    $image = $_FILES["image"];
    $ImageFile = basename($image["name"]);
    $target_dir = "images/";
    $target_file = $target_dir . $ImageFile;

    if ($image["size"] > 5000000) {
        echo "<script>alert('Sorry, your file is too large.');</script>";
    } // Check if the file already exists
    else if (file_exists($target_file)) {
        echo "<script>alert('The file already exists.');</script>";
    } else {
        // The file does not exist. We can proceed to upload the file.
        move_uploaded_file($image["tmp_name"], $target_file);
        $itemDescription = $_POST['itemDescription'];
        $stockItemQty = $_POST['stockItemQty'];
        $price = $_POST['price'];
        $new_item = [$itemID, $supplierID, $itemName, $ImageFile, $itemDescription, $stockItemQty, $price];
        insert_item($new_item);
    }
    echo "<script> window.location.href='item_list.php';</script>";
}
?>

<div class="container">
    <h1>New Item Information</h1>

    <div class="item-list bg-light">
        <form method="post" action="" enctype="multipart/form-data" id="form"
            onsubmit="return confirm('Do you confirm add item?');">
            <label>Item ID: </label><br>
            <input type="text" name="itemID" class="form-control-sm" value="<?php echo get_max_item_id() + 1; ?>"
                readonly><br><br>
            <label>Supplier ID: </label><br>
            <input type="text" name="supplierID" class="form-control-sm" value="<?php echo $id; ?>" readonly><br><br>
            <label>Item Name: </label><br>
            <input type="text" name="itemName" class="form-control-sm" required><br><br>
            <label class="form-label">Image File: </label><br>
            <input type="file" name="image" id="image" class="form-control-sm form-control-file btn btn-light" required
                accept="image/*"
                onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])"><br><br>
            <label for="preview" class="form-label fs-5 mr-2">
                <br>Item Image:</label><br>
            <img src="images/preview.png" name="preview" alt="item image" class="w-15 h-15 p-2 mb-4" id="preview"
                style="width: 200px; height: 200px;"><br><br>
            <label>Item Description: </label><br>
            <textarea name="itemDescription" id="itemDescription" rows="5" cols="10" class="form-control-sm"
                placeholder="Please write a description of the item!" required></textarea><br><br>
            <label>Stock of Item Quantity: </label><br>
            <input type="number" name="stockItemQty" id="stockItemQty" class="form-control-sm" min="0" required><br><br>
            <label>Price: </label><br>
            <input type="number" name="price" id="price" class="form-control-sm" min="1" required><br><br>
            <button type="submit" name="submit" class="btn btn-lg btn-primary" id="submit">Submit
            </button>
            <input type="reset" class="btn btn-lg btn-secondary position-relative" name="reset" style="left: 1%"
                value="reset" onclick="removePreview();">
        </form>
    </div>
</div>

<script>

    function removePreview() {
        document.getElementById("preview").src = "images/preview.png";
    }
</script>
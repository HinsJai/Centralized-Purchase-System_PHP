<?php
if (isset($_POST['update'])) {
    if (!$_FILES["image"]['name'] == null) {
        $image = $_FILES["image"];
        $ImageFile = basename($image["name"]);
        $target_dir = "images/";
        $target_file = $target_dir . $ImageFile;
    } else {
        $ImageFile = $_SESSION['edit_ImageFile'];
    }
    if ($image["size"] > 5000000) {
        echo "<script>alert('Sorry, your file is too large.');</script>";
    } // Check if the file already exists
    else {
        // The file does not exist. We can proceed to upload the file.
        move_uploaded_file($image["tmp_name"], $target_file);
    }
    update_item($_POST['id'], $ImageFile, $_POST['description'], $_POST['stock_qty'], $_POST['price']);

    echo "<script>window.location.href='item_list.php';</script>";
}
?>

<h1>Update Item</h1>
<div class="container">
    <div class="row">
        <div class="col-md-12 position-relative">
            <div class="card">
                <form action="" method="POST" class="form-control p-5" enctype="multipart/form-data">
                    <input type="hidden" name="id" class="form-control" id="id"
                        value="<?php echo $_POST['id'] ?? $_SESSION['edit_itemID']; ?>" readonly>

                    <label for="name" class="form-label fs-5 mr-2">Item Image:</label>
                    <input type="file" name="image" class="form-control fs-5 p-2" accept="image/*" id="image"
                        value="<?php echo isset($_POST['image']) ? $_POST['image'] : $_SESSION['edit_ImageFile']; ?>"
                        onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])"><br>


                    <label for="preview" class="form-label fs-5 mr-2">Item Image:</label>
                    <img src="images/<?php echo $_POST['image'] ?? $_SESSION['edit_ImageFile']; ?>" alt="item image"
                        class="w-15 h-15 p-2 mb-4" id="preview">


                    <div class="">
                        <label for="description" class="form-label fs-5">Description:</label>
                        <textarea name="description" cols="50" rows="10" class="form-control  mb-4" id="description"
                            required><?php echo $_POST['description'] ?? $_SESSION['edit_itemDescription']; ?></textarea>
                    </div>


                    <label for=" stock_qty" class="form-label fs-5">Stock Quantity:</label>
                    <input type="number" name="stock_qty" class="form-control fs-5 mb-4" id="stock_qty"
                        value="<?php echo $_POST['stock_qty'] ?? $_SESSION['edit_stockItemQty']; ?>" required min="0">

                    <label for="price" class="form-label fs-5">Price:</label>
                    <input type="number" name="price" class="form-control fs-5" id="price"
                        value="<?php echo $_POST['price'] ?? $_SESSION['edit_price']; ?>" required min="1"><br><br>

                    <input type="submit" name="update" id="updateItem" value="update" class="btn btn-lg btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// $(document).ready(function () {
//     $("#updateItem").click(function (e) {
//         e.preventDefault();
//
//         $.confirm({
//             title: 'Submit Confirmation',
//             content: 'Do you want to submit the order?',
//             type: 'blue',
//             typeAnimated: true,
//             buttons: {
//                 confirm: {
//                     text: 'Yes',
//                     btnClass: 'btn-red',
//                     action: function () {
//
//                         $.ajax({
//                             url: 'edit_item_form.php',
//                             type: 'POST',
//                             data: {
//                                 id: $('#id').val(),
//                                 image: $('#image').prop('files')[0],
//                                 description: $('#description').val(),
//                                 stock_qty: $('#stock_qty').val(),
//                                 price: $('#price').val()
//                             },
//                             processData: false,
//                             contentType: false,
//                             success: function (response) {
//
//                             },
//                             error: function (xhr, status, error) {
//
//                                 alert("An error occurred while submitting the order. Please try again.");
//                                 console.error(error);
//                             }
//                         });
//
//                         swal({
//                             title: 'Successful Submission!',
//                             text: '',
//                             icon: 'success',
//                             button: 'OK'
//                         }).then(function () {
//                             window.location = 'item_list.php';
//                         });
//                     }
//                 },
//                 cancel: {
//                     text: 'No',
//                     action: function () {
//
//                     }
//                 }
//             },
//             columnClass: 'alert-dialog'
//         });
//     });
// });

// function updateItem() {
//
//     $.ajax({
//         url: 'insert_item.php',
//         type: 'POST',
//         data: formData,
//         processData: false,
//         contentType: false,
//         success: function (response) {
//             // Handle the success response
//         },
//         error: function (xhr, status, error) {
//             // Handle the error response
//             alert("An error occurred while submitting the order. Please try again.");
//             console.error(error);
//         }
//     });
// }
</script>
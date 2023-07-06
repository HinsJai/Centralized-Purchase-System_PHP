<?php
include_once('db.php');
$id = $_POST['id'];
if (!is_busy($id)) {
    $image = get_item_image_by_id($id);
    unlink('images/' . $image);
    delete_item($id);
} else {
    echo "<script>alert('Item has related order(s), CANNOT delete!');</script>";
}
echo "<script>window.location.href='item_list.php';</script>";
?>
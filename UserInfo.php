<?php
$pageTitle = 'Account';
include './includes/header.php';
global $conn;
$userID = $_SESSION['userID'];

//check the role is  purchase manager or supplier
$role = "";
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    if (str_starts_with($userID, 'p')) {
        $role = "Purchase Manager";
    } else if (str_starts_with($userID, 's')) {
        $role = "Supplier";
    }
}

if ($role == "Purchase Manager") {
    $sql = "SELECT * FROM purchasemanager WHERE purchaseManagerID = '$userID'";
} else if ($role == "Supplier") {
    $sql = "SELECT * FROM supplier WHERE supplierID = '$userID'";
}
$result = mysqli_query($conn, $sql);
$rc = mysqli_fetch_assoc($result);
extract($rc);
?>
<?php
if ($role == "Purchase Manager") {
    ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 position-relative">
            <div class="card">
                <div class="card-header fs-3">
                    <h3 class="text-center">User Profile</h3>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-4 mt-4">
                            <label for="userID" class="form-label fs-3">User ID</label>
                            <input type="text" class="form-control fs-5" id="userID" name="userID"
                                value="<?php echo $userID ?>" readonly>
                        </div>
                        <div class="mb-4">
                            <label for="name" class="form-label fs-3">Name</label>
                            <input type="text" class="form-control fs-5" id="name" name="name"
                                value="<?php echo $managerName ?>" readonly>
                        </div>
                        <div class="mb-4">
                            <label for="contact" class="form-label fs-3">Contact Number <span
                                    class="fs-5 m-lg-4 text-danger" id="reminder" hidden>*Accept 8 number
                                    only</span></label>
                            <input type="text" class="form-control fs-5" id="contact" title="Please enter 8 number"
                                name="name" value="<?php echo $contactNumber ?>" readonly>
                        </div>
                        <div class="mb-4">
                            <label for="warehouse-address" class="form-label fs-3">Warehouse Address</label>
                            <textarea class="form-control fs-5" cols="30" rows="8" id="warehouse-address" readonly><?php echo $warehouseAddress ?>
                                                        </textarea>
                        </div>
                        <div class="d-grid gap-2 d-md-block">
                            <button class="btn btn-primary btn-lg" id="edit">Edit</button>
                            <button class="btn btn-secondary btn-lg" id="cancel" hidden>Cancel</button>
                            <button class="btn btn-primary btn-lg float-right" id="update" hidden>Update</button>
                            <a href="UpdatePassword.php" class="btn btn-primary btn-lg bg-dark-blue float-right"
                                id="cPwd">Change Password</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
} else if ($role == "Supplier") {
    ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 position-relative">
            <div class="card">
                <div class="card-header fs-3">
                    <h3 class="text-center">User Profile</h3>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-4 mt-4">
                            <label for="supUserID" class="form-label fs-3">User ID</label>
                            <input type="text" class="form-control fs-5" id="supUserID" name="supUserID"
                                value="<?php echo $userID ?>" readonly>
                        </div>
                        <div class="mb-4">
                            <label for="supContactName" class="form-label fs-3">Name</label>
                            <input type="text" class="form-control fs-5" id="supContactName" name="supContactName"
                                value="<?php echo $contactName ?>" readonly>
                        </div>
                        <div class="mb-4">
                            <label for="supReminder" class="form-label fs-3">Contact Number <span
                                    class="fs-5 m-lg-4 text-danger" id="supReminder" hidden>*Accept 8 number
                                    only</span></label>
                            <input type="text" class="form-control fs-5" id="supContact" title="Please enter 8 number"
                                name="supContact" value="<?php echo $contactNumber ?>" readonly>
                        </div>
                        <div class="mb-4">
                            <label for="supAddress" class="form-label fs-3">Supplier Address</label>
                            <textarea class="form-control fs-5" cols="30" rows="8" id="supAddress" readonly><?php echo $address ?>
                                                                                        </textarea>
                        </div>
                        <div class="d-grid gap-2 d-md-block">
                            <button class="btn btn-primary btn-lg" id="supEdit">Edit</button>
                            <button class="btn btn-secondary btn-lg" id="supCancel" hidden>Cancel</button>
                            <button class="btn btn-primary btn-lg float-right" id="supUpdate" hidden>Update</button>
                            <a href="UpdatePassword.php" class="btn btn-primary btn-lg bg-dark-blue float-right"
                                id="supPwd">Change Password</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>

<?php include './includes/footer.php'; ?>
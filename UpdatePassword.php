<?php
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

if (isset($_POST['update_pswd_btn'])) {
    if ($role == "Purchase Manager") {
        $sql = "SELECT password FROM purchasemanager WHERE purchaseManagerID = '$userID'";
    } else if ($role == "Supplier") {
        $sql = "SELECT password FROM supplier WHERE supplierID = '$userID'";
    }
    $result = mysqli_query($conn, $sql);
    $rc = mysqli_fetch_assoc($result);
    extract($rc);

    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $cpassword = $_POST['cpassword'];

    if ($oldPassword == $password) {
        if ($newPassword == $cpassword) {
            if ($role == "Purchase Manager") {
                $sql = "UPDATE purchasemanager SET password = '$newPassword' WHERE purchaseManagerID = '$userID'";
            } else if ($role == "Supplier") {
                $sql = "UPDATE supplier SET password = '$newPassword' WHERE supplierID = '$userID'";
            }
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $_SESSION['message'] = "Password Updated Successfully";
                $_SESSION['success'] = true;
            } else {
                $_SESSION['message'] = "Password Updated Failed!";
                $_SESSION['success'] = false;
            }
        } else {
            $_SESSION['message'] = "New Password do not match with Confirm Password!";
            $_SESSION['success'] = false;
        }
    } else {
        $_SESSION['message'] = "Old Password do not match with Current Password!";
        $_SESSION['success'] = false;
    }
}
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                        <path
                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                    </symbol>
                    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                    </symbol>
                </svg>
                <?php
                if (isset($_SESSION['message']) && $_SESSION['success'] == false) {
                    ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                            <use xlink:href="#exclamation-triangle-fill" />
                        </svg>
                        <div class="fs-4">
                            <?php echo $_SESSION['message'] ?>
                        </div>
                    </div>
                    <?php
                    unset($_SESSION['message'], $_SESSION['success']);
                } else if (isset($_SESSION['message']) && $_SESSION['success'] == true) {
                    ?>
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                                <use xlink:href="#check-circle-fill" />
                            </svg>
                            <div class="fs-4">
                            <?php echo $_SESSION['message'] ?>
                            </div>
                        </div>
                        <?php
                        unset($_SESSION['message'], $_SESSION['success']);
                }
                ?>
                <div class="card">
                    <div class="card-header">
                        <h2>Change Password
                            <a href="UserInfo.php" class="btn btn-warning fw-bold float-end"><i class="fa fa-reply"></i>
                                Back</a>
                        </h2>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label class="form-label fs-4">Old Password</label>
                                <input type="password" class="form-control fs-5" name="oldPassword" id="oldPassword"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fs-4">New Password
                                    <span class="alert alert-danger align-items-center p-1 ml-4 pattern_alert"
                                        role="alert" hidden>
                                        <svg class="bi flex-shrink-0 me-2" width="40" height="24" role="img"
                                            aria-label="Danger:">
                                            <use xlink:href="#exclamation-triangle-fill" />
                                        </svg>
                                        <span class="fs-5 p-2">Pattern not match</span>
                                    </span>
                                </label>
                                <div class="fs-6 reminder text-danger mb-2">* 8 charater or above, the first charater
                                    should alphabet and include upper, lower case.</div>
                                <input type="password" class="form-control fs-5" name="newPassword" id="newPassword"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fs-4">Confirm New Password</label>
                                <input type="password" name="cpassword" class="form-control fs-5" id="cpassword"
                                    required>
                            </div>
                            <button type="submit" name="update_pswd_btn" class="btn btn-primary btn-lg float-right mt-4"
                                id="submit_btn" disabled>Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#newPassword, #oldPassword, #cpassword").on("change", function () {

            //8 charater or above, the first charater should alphabet and include upper, lower case
            var newPassword = $('#newPassword').val();
            var pattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

            if (!pattern.test(newPassword) && newPassword != "") {
                $(".pattern_alert").removeAttr("hidden");

            } else {
                $(".pattern_alert").attr("hidden", true);
            }
            if ($("#newPassword").val() !== "" && $("#oldPassword").val() !== "" && $("#cpassword")
                .val() !== "" && $("#newPassword").val() == $("#cpassword").val() && pattern.test(
                    newPassword)
            ) {
                $("#submit_btn").attr("disabled", false);
            } else {
                $("#submit_btn").attr("disabled", true);
            }
        });
    });
</script>

<?php include './includes/footer.php'; ?>
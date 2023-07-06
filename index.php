<?php
session_start();
session_destroy();
$pageTitle = 'Login';
include 'DBConn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['userID']) && isset($_POST['password'])) {

        verifyLogin();
    }
}

function setRememberMeCookie($userID)
{
    $cookie_name = 'remember_me';
    $cookie_value = $_POST['userID'];
    $cookie_duration = time() + (30 * 24 * 60 * 60); // Set cookie to expire after 30 days

    setcookie($cookie_name, $cookie_value, $cookie_duration, '/');
}

function clearRememberMeCookie()
{
    if (isset($_COOKIE['remember_me'])) {
        unset($_COOKIE['remember_me']);
        setcookie('remember_me', null, -1, '/');
    }
}

function verifyLogin()
{
    $userID = $_POST['userID'];
    $password = $_POST['password'];
    $sql = "";
    global $conn;

    // Escape user inputs to prevent SQL injection
    $userID = mysqli_real_escape_string($conn, $userID);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if the user exists in the PurchaseManager table
    if ($_POST["user"] === "purchase") {
        $sql = "SELECT * FROM purchasemanager WHERE purchaseManagerID = '$userID' AND password = '$password'";
    } else if ($_POST["user"] === "supplier") {
        $sql = "SELECT * FROM supplier WHERE supplierID = '$userID' AND password = '$password'";
    }

    $result = mysqli_query($conn, $sql);

    // Check if the user exists in the PurchaseManager or supplier table and assign the session variable
    if (mysqli_num_rows($result) > 0) {
        session_start();
        $_SESSION['userID'] = $_POST['userID'];

        if (isset($_POST['remember'])) {
            setRememberMeCookie($_SESSION['userID']);
        } else {
            clearRememberMeCookie();
        }

        // $row = mysqli_fetch_assoc($result);

        if ($_POST["user"] === "purchase") {
            // Valid purchase manager, redirect to CreateOrder.php
            header("Location: ProductList.php");
        } else if ($_POST["user"] === "supplier") {
            // Valid supplier, redirect to item_list.php
            header("Location: item_list.php");
        }
    } else {
        // Invalid login, set session variable to indicate the error
        $_SESSION['login_error'] = true;
    }

    mysqli_free_result($result);
    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Yummy Group Login</title>
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="assets/css/material-dashboard.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />
    <link rel="stylesheet" href="style/CreateOrder.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="assets/js/core/bootstrap.bundle.min.js"></script>
    <script src="assets/js/plugins/dselect.js"></script>
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/00a35a7ebb.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.0/moment.min.js"></script>
    <script src="https://kit.fontawesome.com/00a35a7ebb.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
</head>

<body>

    <div class="py-8" style="background-image: url('images/login_bg.png'); height: 100vmin; background-size:cover;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7">
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
                    <div class="d-flex justify-content-center mb-6">
                        <h1 class="fw-bold">Centralized Procurement System</h1>
                    </div>
                    <div class="card rounded">
                        <div class="card-header d-flex justify-content-center bg-dark text-white rounded-top">
                            <h2 class="fw-bold">Login</h2>
                        </div>
                        <div class="card-body rounded-bottom">
                            <form action="" method="post" class="needs-validation">
                                <div class="mb-3 form-group was-validated">
                                    <label class="form-label fs-4">User ID</label>
                                    <input type="text" class="form-control fs-5" name="userID" id="userID"
                                        value="<?php echo isset($_COOKIE['remember_me']) ? $_COOKIE['remember_me'] : ''; ?>"
                                        required>

                                    <div class="invalid-feedback fs-5">
                                        Please enter your user ID
                                    </div>
                                </div>
                                <div class="mb-3 form-group was-validated">
                                    <label class="form-label fs-4">Password</label>
                                    <input type="password" class="form-control fs-5" name="password" id="password"
                                        required>
                                    <div class="invalid-feedback fs-5" id="password_reminder">
                                        Please enter your user password
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" name="remember"
                                            id="remember"
                                            <?php echo isset($_COOKIE['remember_me']) ? 'checked' : ''; ?>>
                                        <label class="fs-5 form-check-label" for="remember">
                                            Remember me
                                        </label>
                                    </div>
                                    <div class="form-group mt-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="user" id="purchase"
                                                value="purchase"
                                                <?php echo (isset($_COOKIE['remember_me']) && substr($_COOKIE['remember_me'], 0, 1) === 'p') ? 'checked' : ''; ?>
                                                checked>
                                            <label class="form-check-label fs-5" for="purchase">
                                                Purchase
                                            </label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="radio" name="user" value="supplier"
                                                id="supplier"
                                                <?php echo (isset($_COOKIE['remember_me']) && substr($_COOKIE['remember_me'], 0, 1) === 's') ? 'checked' : ''; ?>>
                                            <label class="form-check-label fs-5" for="supplier">
                                                Supplier
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 d-flex justify-content-center">
                                    <button type="submit" name="login_btn" class="btn btn-dark btn-lg w-20"
                                        id="login_btn">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include './includes/footer.php'; ?>
    </div>
</body>

</html>

<script>
// Check if the session variable indicating a login error is set
<?php if (isset($_SESSION['login_error']) && $_SESSION['login_error']) { ?>
$.alert({
    title: 'Error!',
    content: 'Incorrect userID or password. Please try again.',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Try again',
            btnClass: 'btn-red',
            action: function() {}
        }
    },
    columnClass: 'alert-dialog'
});
<?php
        unset($_SESSION['login_error']);
    }
    ?>
</script>
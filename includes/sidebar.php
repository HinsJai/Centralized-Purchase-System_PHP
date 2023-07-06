<?php
global $pageTitle;
$userID = "";
$role = "";
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
}

$bgColorProductList = '';
$bgColorOrderRecord = '';
$bgColorAccount = '';
if ($pageTitle == 'Product List') {
    $bgColorProductList = 'bg-info';
} else if ($pageTitle == 'Order Record') {
    $bgColorOrderRecord = 'bg-info';
} else if ($pageTitle == 'Account') {
    $bgColorAccount = 'bg-info';
}

//check the role is  purchase manager or supplier
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    if (str_starts_with($userID, 'p')) {
        $role = "Purchase Manager";
    } else if (str_starts_with($userID, 's')) {
        $role = "Supplier";
    }
}
?>
<?php
if ($role == "Purchase Manager") {
    ?>
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 fixed-start bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <div class="navbar-brand m-0">
            <span class="ms-1 font-weight-bold text-white text-2xl">Purchase System</span>
        </div>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item <?php echo $bgColorProductList ?>">
                <a class="nav-link text-white" href="HeaderFunctions.php?op=home">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">add_shopping_cart</i>
                    </div>
                    <span class="nav-link-text ms-1 fs-5">Product List</span>
                </a>
            </li>
            <li class="nav-item <?php echo $bgColorOrderRecord ?>">
                <a class="nav-link text-white" href="HeaderFunctions.php?op=record">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">history</i>
                    </div>
                    <span class="nav-link-text ms-1 fs-5">Order Record</span>
                </a>
            </li>
            <li class="nav-item <?php echo $bgColorAccount ?>">
                <a class="nav-link text-white " href="HeaderFunctions.php?op=staff">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10 fs-5">account_circle</i>
                    </div>
                    <span class="nav-link-text ms-1 fs-5">Account</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 mb-7">
        <div class="mx-3 text-white fs-6 mb-2">
            User ID:
            <?php echo $userID ?>
        </div>
        <div class="mx-3 text-white fs-6">
            Role:
            <?php echo $role ?>
        </div>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 logout">
        <div class="mx-3">
            <a class="btn bg-gradient-primary mt-4 w-100" type="button"><i
                    class="fa-solid fa-right-from-bracket fa-lg logout_icon"></i>Logout</a>
        </div>
    </div>
</aside>
<?php
} else if ($role == "Supplier") {
    ?>
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 fixed-start bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <div class="navbar-brand m-0">
            <span class="ms-1 font-weight-bold text-white text-2xl">Supplier System</span>
        </div>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item <?php echo $bgColorProductList ?>">
                <a class="nav-link text-white" href="HeaderFunctions.php?op=sup_home">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">add_shopping_cart</i>
                    </div>
                    <span class="nav-link-text ms-1 fs-5">Item List</span>
                </a>
            </li>
            <li class="nav-item <?php echo $bgCoslorOrderRecord ?>">
                <a class="nav-link text-white" href="HeaderFunctions.php?op=sup_report">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">history</i>
                    </div>
                    <span class="nav-link-text ms-1 fs-5">Sales Report</span>
                </a>
            </li>
            <li class="nav-item <?php echo $bgColorAccount ?>">
                <a class="nav-link text-white " href="HeaderFunctions.php?op=sup_staff">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10 fs-5">account_circle</i>
                    </div>
                    <span class="nav-link-text ms-1 fs-5">Account</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 mb-7">
        <div class="mx-3 text-white fs-6 mb-2">
            User ID:
            <?php echo $userID ?>
        </div>
        <div class="mx-3 text-white fs-6">
            Role:
            <?php echo $role ?>
        </div>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 logout">
        <div class="mx-3">
            <a class="btn bg-gradient-primary mt-4 w-100" type="button"><i
                    class="fa-solid fa-right-from-bracket fa-lg logout_icon"></i>Logout</a>
        </div>
    </div>
</aside>
<?php
}
?>
<?php
include 'CheckValidUser.php';
include 'DBConn.php';

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
}

global $pageTitle;


?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>
            Purchase system
        </title>
        <link rel="stylesheet" type="text/css"
              href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700"/>

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
        <link id="pagestyle" href="assets/css/material-dashboard.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
        <link rel="stylesheet" href="style/CreateOrder.css"/>
        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
        <script src="assets/js/core/bootstrap.bundle.min.js"></script>
        <script src="assets/js/plugins/dselect.js"></script>
        <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/00a35a7ebb.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.0/moment.min.js"></script>
        <script src="https://kit.fontawesome.com/00a35a7ebb.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
    </head>

<body class="g-sidenav-show  bg-gray-200" <?php
if ($pageTitle == 'Create Order') {
    echo 'startTimer();"';
}
?>>

<?php include './includes/sidebar.php'; ?>
    <style>
        main {
            margin-top: 5%;
            margin-left: 12%;
        }
    </style>
    <main class="main-content position-relative max-height-vh-100 h-auto border-radius-lg">
<?php include './includes/navbar.php'; ?>
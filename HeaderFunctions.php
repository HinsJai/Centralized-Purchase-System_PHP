<?php
if ($_GET['op'] == 'logout') {
    logout();
} else if ($_GET['op'] == 'home') {
    home();
} else if ($_GET['op'] == 'record') {
    record();
} else if ($_GET['op'] == 'staff') {
    staff();
} else if ($_GET['op'] == 'sup_home') {
    sup_home();
} else if ($_GET['op'] == 'sup_report') {
    sup_report();
} else if ($_GET['op'] == 'sup_staff') {
    sup_staff();
}

function sup_home()
{
    header("Location: item_list.php");
}

function sup_report()
{
    header("Location: generate_report.php");
}

function sup_staff()
{
    header("Location: UserInfo.php");
}


function logout()
{
    // destroy the session of login user
    session_start();
    unset($_SESSION['userID']);
    session_destroy();
    header("Location: index.php");
    exit();
}

function home()
{
    header("Location: ProductList.php");
}

function staff()
{
    header("Location: UserInfo.php");
}

function record()
{
    header("Location: OrderRecord.php");
}
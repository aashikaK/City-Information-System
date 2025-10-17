<?php
require "db.php";
include "navbar.php";
// Allow only logged-in users
if(!isset($_SESSION['login']) || $_SESSION['login'] == ''){
    header("Location: signin.php");
    exit();
}
?>

<?php
session_start();
require_once 'functions.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}
?>

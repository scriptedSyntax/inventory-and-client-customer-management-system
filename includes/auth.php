<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: /africa_project/auth/login.php");
    exit;
}

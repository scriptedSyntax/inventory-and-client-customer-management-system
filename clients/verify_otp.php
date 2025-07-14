<?php
session_start();
require_once "../config/db.php";
require_once "../includes/functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Temporarily bypass OTP check for pilot/testing
    $data = $_SESSION['client_data'];

    // ✅ Use insert() instead of raw SQL
    insert('clients', [
        'client_id' => $data['client_id'],
        'client_name' => $data['client_name'],
        'client_phone' => $data['client_phone'],
        'guarantor_id' => $data['guarantor_id']
    ]);

    unset($_SESSION['otp'], $_SESSION['client_data']);
    header("Location: view.php?success=1");
    exit;
}

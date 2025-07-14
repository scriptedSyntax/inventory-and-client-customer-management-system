<?php
require_once "../config/db.php";

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    // ✅ Use update helper
    update('rentals', ['payment_status' => 'Paid'], ['id' => $id]);
}

header("Location: unpaid.php?updated=1");
exit;

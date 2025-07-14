<?php
require_once "../config/db.php";

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    // ✅ Use update helper
    update('equipment', ['status' => 'available'], ['id' => $id]);
}

header("Location: list.php?returned=1");
exit;

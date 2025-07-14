<?php
require_once "../config/db.php";

$id = $_GET['id'] ?? 0;

if ($id) {
    delete('equipment', ['id' => $id]);
}

header("Location: ../dashboards/equipment.php?deleted=1");
exit;

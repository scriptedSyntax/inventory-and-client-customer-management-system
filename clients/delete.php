<?php
require_once "../config/db.php";

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    delete('clients', ['id' => $id]);
    $_SESSION['flash'] = "Client deleted.";
}

header("Location: ../dashboards/clients.php");
exit;

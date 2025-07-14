<?php
require '../config/db.php';
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="clients.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Name', 'Client ID', 'Phone', 'Guarantor ID']);

$stmt = $pdo->query("SELECT * FROM clients");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [$row['client_name'], $row['client_id'], $row['client_phone'], $row['guarantor_id']]);
}
fclose($output);
exit;

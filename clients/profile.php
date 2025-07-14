<?php
require_once "../config/db.php";
require_once "../includes/header.php";

$id = $_GET['id'] ?? 0;

// ✅ Use fetchOne()
$client = fetchOne('clients', ['id' => $id]);

if (!$client) {
    echo "Client not found.";
    include_once "../includes/footer.php";
    exit;
}
?>

<div class="card p-4">
    <h2><?= htmlspecialchars($client['client_name']) ?> (<?= htmlspecialchars($client['client_id']) ?>)</h2>
    <p><strong>Phone:</strong> <?= htmlspecialchars($client['client_phone']) ?></p>
    <p><strong>Guarantor ID:</strong> <?= htmlspecialchars($client['guarantor_id']) ?></p>

    <hr class="my-4">
    <h4>Rental History</h4>
    <p><em>Coming soon: linked rentals, debts, and equipment log…</em></p>
</div>

<?php include_once "../includes/footer.php"; ?>

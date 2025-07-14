<?php
require_once "../config/db.php";
require_once "../includes/header.php";

// ✅ Use fetchAll()
$clients = fetchAll('clients');
?>

<div class="card p-4">
    <h2 class="text-xl font-bold mb-4">All Clients</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Phone</th><th>Guarantor</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= htmlspecialchars($client['client_id']) ?></td>
                <td><?= htmlspecialchars($client['client_name']) ?></td>
                <td><?= htmlspecialchars($client['client_phone']) ?></td>
                <td><?= htmlspecialchars($client['guarantor_id']) ?></td>
                <td><a href="profile.php?id=<?= $client['id'] ?>" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once "../includes/footer.php"; ?>

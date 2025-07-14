<?php
require_once "../config/db.php";
require_once "../includes/header.php";

// ✅ Use fetchAll helper
$equipment = fetchAll('equipment');
?>

<div class="card p-4">
    <h2 class="text-xl font-bold mb-4">Equipment List</h2>
    <a href="add.php" class="btn btn-primary mb-3">+ Add New</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th><th>Type</th><th>Daily Rate</th><th>Category</th><th>Status</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipment as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars($item['type']) ?></td>
                <td>KES <?= number_format($item['daily_rate'], 2) ?></td>
                <td><?= htmlspecialchars($item['category']) ?></td>
                <td>
                    <span class="badge <?= $item['status'] == 'available' ? 'bg-success' : 'bg-warning' ?>">
                        <?= htmlspecialchars($item['status']) ?>
                    </span>
                </td>
                <td>
                    <?php if ($item['status'] == 'rented'): ?>
                        <a href="return.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-success">Mark Returned</a>
                    <?php else: ?>
                        <span class="text-muted">Available</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once "../includes/footer.php"; ?>

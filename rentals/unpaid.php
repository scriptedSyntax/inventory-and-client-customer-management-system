<?php
require_once "../config/db.php";
require_once "../includes/header.php"; // Ensure the updated header.php is included
require_once "../includes/functions.php"; // Needed for statusBadge and potentially other helpers

// Manual JOIN is still required here to get client and equipment names
$debts = $pdo->query("
    SELECT rentals.*, clients.client_name AS client_name, equipment.name AS equipment_name
    FROM rentals
    JOIN clients ON rentals.client_id = clients.id
    JOIN equipment ON rentals.equipment_id = equipment.id
    WHERE rentals.payment_status = 'To Be Paid'
    ORDER BY rentals.id DESC
")->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array for consistency
?>

<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Unpaid Rentals</h2>

    <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Due</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Rented</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($debts)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No outstanding debts found. Everything is paid up!</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($debts as $d): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($d['client_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($d['equipment_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">KES <?= number_format($d['amount_due'], 2) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d M Y', strtotime($d['rental_date'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="mark_paid.php?id=<?= $d['id'] ?>" class="text-green-600 hover:text-green-900">Mark Paid</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "../includes/footer.php"; ?>
<?php
require_once "../config/db.php";
require_once "../includes/header.php"; // Updated header
require_once "../includes/functions.php"; // Ensure functions.php is included for statusBadge()

// Keeping JOIN because it fetches related names efficiently
$rentals = $pdo->query("
    SELECT rentals.*, clients.client_name AS client_name, equipment.name AS equipment_name
    FROM rentals
    JOIN clients ON rentals.client_id = clients.id
    JOIN equipment ON rentals.equipment_id = equipment.id
    ORDER BY rentals.rental_date DESC
")->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array
?>

<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-800">All Rentals</h2>
        <a href="new.php" class="btn-success inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Create New Rental
        </a>
    </div>

    <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rental Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($rentals)) : ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No rentals found.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($rentals as $r): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($r['client_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($r['equipment_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">KES <?= number_format($r['amount_due'], 2) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?= statusBadge($r['payment_status']) // Uses the statusBadge from functions.php ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d M Y', strtotime($r['rental_date'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <?php if ($r['payment_status'] === 'To Be Paid'): ?>
                                    <a href="mark_paid.php?id=<?= $r['id'] ?>" class="text-green-600 hover:text-green-900">Mark Paid</a>
                                <?php else: ?>
                                    <span class="text-gray-400">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "../includes/footer.php"; ?>
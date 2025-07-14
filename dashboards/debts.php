<?php
include '../includes/header.php';
require '../config/db.php';
require '../includes/functions.php';

$debts = fetchAll('rentals', ['payment_status' => 'To Be Paid']);

// Helper function for status badges (if not already in functions.php)
// function statusBadge($status) {
//     switch ($status) {
//         case 'To Be Paid':
//             return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">To Be Paid</span>';
//         case 'Paid':
//             return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>';
//         default:
//             return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">' . htmlspecialchars($status) . '</span>';
//     }
// }
?>

<div class="bg-white shadow-sm rounded-lg p-6 mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Outstanding Debts</h2>

    <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Due</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($debts)) : ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No outstanding debts found. Good job!</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($debts as $rental) : ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?php
                                $client = fetchOne('clients', ['id' => $rental['client_id']]);
                                echo $client ? htmlspecialchars($client['client_name']) : 'Unknown Client';
                                ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php
                                $equipment = fetchOne('equipment', ['id' => $rental['equipment_id']]);
                                echo $equipment ? htmlspecialchars($equipment['name']) : 'Unknown Equipment';
                                ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">KES <?= number_format($rental['amount_due'], 2) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?= statusBadge($rental['payment_status']) // Assuming statusBadge function exists or use the helper from above ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="../rentals/mark_paid.php?id=<?= $rental['id'] ?>" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Mark Paid
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
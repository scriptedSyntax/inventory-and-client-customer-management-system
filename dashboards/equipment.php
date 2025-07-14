<?php
include '../includes/header.php';
require '../config/db.php';
require '../includes/functions.php';

// Optional filter logic
$filter = $_GET['filter'] ?? 'all';

if ($filter === 'available') {
    $equipment = fetchAll('equipment', ['status' => 'available']);
} elseif ($filter === 'rented') {
    $equipment = fetchAll('equipment', ['status' => 'rented']);
} else {
    $equipment = fetchAll('equipment');
}

// === IMPORTANT: Define statusBadge function if it's not in functions.php ===
// This function helps apply consistent Tailwind CSS styles based on equipment status.
if (!function_exists('statusBadge')) {
    function statusBadge($status) {
        switch ($status) {
            case 'available':
                return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Available</span>';
            case 'rented':
                return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Rented</span>';
            // Add more cases here if you have other statuses (e.g., 'under_maintenance', 'damaged')
            // This 'default' case will catch any status not explicitly defined above.
            default:
                return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">' . htmlspecialchars(ucfirst($status)) . '</span>';
        }
    }
}
// ===========================================================================
?>

<div class="bg-white shadow-sm rounded-lg p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Equipment Management</h2>
        <div class="flex flex-wrap gap-2">
            <a href="../equipment/add.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add New Equipment
            </a>
            <div class="relative inline-flex rounded-md shadow-sm">
                <a href="equipment.php?filter=all" class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 <?= $filter === 'all' ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : '' ?>">
                    All
                </a>
                <a href="equipment.php?filter=available" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 <?= $filter === 'available' ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : '' ?>">
                    Available
                </a>
                <a href="equipment.php?filter=rented" class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 <?= $filter === 'rented' ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : '' ?>">
                    Rented
                </a>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daily Rate</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($equipment)) : ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No equipment found for this filter.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($equipment as $item) : ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($item['name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($item['type']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">KES <?= number_format($item['daily_rate'], 2) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($item['category']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?= statusBadge($item['status']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="../equipment/edit.php?id=<?= $item['id'] ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <a href="../equipment/delete.php?id=<?= $item['id'] ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this equipment? This action cannot be undone.');">Delete</a>
                                <?php if ($item['status'] === 'rented') : ?>
                                    <a href="../equipment/return.php?id=<?= $item['id'] ?>" class="text-green-600 hover:text-green-900">Return</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
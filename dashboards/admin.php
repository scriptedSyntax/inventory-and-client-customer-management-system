<?php
require '../config/db.php';
require '../includes/functions.php';
require '../includes/auth.php';
include '../includes/header.php';

// Summary counts
$clientCount = count(fetchAll('clients'));
$equipmentCount = count(fetchAll('equipment'));
$unpaidRentals = count(fetchAll('rentals', ['payment_status' => 'To Be Paid']));
$equipmentAvailable = count(fetchAll('equipment', ['status' => 'available']));
$equipmentRented = count(fetchAll('equipment', ['status' => 'rented']));
?>

<div class="bg-white p-6 shadow-sm rounded-lg mb-6">
    <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Welcome to Your Dashboard</h2>
    <p class="text-gray-600">Get a quick overview of your film and equipment rental operations.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <a href="clients.php" class="block">
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-blue-200">
            <h5 class="text-lg font-semibold text-gray-700 mb-2">Total Clients</h5>
            <p class="text-4xl font-bold text-blue-600"><?= $clientCount ?></p>
        </div>
    </a>

    <a href="equipment.php" class="block">
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-purple-200">
            <h5 class="text-lg font-semibold text-gray-700 mb-2">Total Equipment</h5>
            <p class="text-4xl font-bold text-purple-600"><?= $equipmentCount ?></p>
        </div>
    </a>

    <a href="debts.php" class="block">
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-red-200">
            <h5 class="text-lg font-semibold text-gray-700 mb-2">Unpaid Rentals</h5>
            <p class="text-4xl font-bold text-red-600"><?= $unpaidRentals ?></p>
        </div>
    </a>

    <a href="/africa_project/rentals/new.php" class="block">
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-green-200">
            <h5 class="text-lg font-semibold text-gray-700 mb-2">New Rental</h5>
            <p class="text-4xl font-bold text-green-600 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Rent
            </p>
        </div>
    </a>
</div>

<div class="bg-white p-6 shadow-sm rounded-lg mb-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Current Equipment Status</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-green-50 p-4 rounded-lg flex items-center justify-between shadow-sm">
            <div>
                <p class="text-md font-semibold text-gray-700">Available Equipment</p>
                <p class="text-2xl font-bold text-green-700"><?= $equipmentAvailable ?></p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg flex items-center justify-between shadow-sm">
            <div>
                <p class="text-md font-semibold text-gray-700">Rented Equipment</p>
                <p class="text-2xl font-bold text-yellow-700"><?= $equipmentRented ?></p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8l4-4 4 4V7m0 3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <h3 class="text-xl font-bold text-gray-800 mb-4">Recently Added Equipment</h3>
    <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daily Rate</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php
                // Fetch all equipment and then get the 5 most recent by id descending
                $allEquipment = fetchAll('equipment', []);
                usort($allEquipment, function($a, $b) {
                    return $b['id'] <=> $a['id'];
                });
                $recentEquipment = array_slice($allEquipment, 0, 5);
                if (empty($recentEquipment)) : ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No equipment added yet.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($recentEquipment as $item) : ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($item['name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($item['type']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">KES <?= number_format($item['daily_rate']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($item['category']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    <?= $item['status'] == 'available' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                    <?= ucfirst($item['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="text-right mt-4">
        <a href="equipment.php" class="text-blue-600 hover:text-blue-800 font-medium text-sm">View All Equipment &rarr;</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
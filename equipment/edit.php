<?php
require_once "../config/db.php";
require_once "../includes/header.php";
require_once "../includes/functions.php"; // Ensure functions.php is included for fetchOne(), update() and flash()

$id = $_GET['id'] ?? 0;
$equipment = fetchOne('equipment', ['id' => $id]);

if (!$equipment) {
    flash("Equipment not found.", 'error');
    header("Location: ../dashboards/equipment.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => trim($_POST['name']),
        'type' => trim($_POST['type']),
        'daily_rate' => floatval($_POST['daily_rate']),
        'category' => trim($_POST['category']),
        'status' => trim($_POST['status'])
    ];

    $success = update('equipment', $data, ['id' => $id]);

    if ($success) {
        flash("Equipment '{$data['name']}' updated successfully!", 'success');
        header("Location: ../dashboards/equipment.php");
    } else {
        flash("Failed to update equipment. Please try again.", 'error');
        header("Location: edit.php?id={$id}");
    }
    exit;
}
?>

<div class="bg-white shadow-md rounded-lg p-6 mb-6 max-w-lg mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Equipment: <?= htmlspecialchars($equipment['name']) ?></h2>
    <form method="POST" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Equipment Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($equipment['name']) ?>" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
        </div>
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <input type="text" id="type" name="type" value="<?= htmlspecialchars($equipment['type']) ?>" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
        </div>
        <div>
            <label for="daily_rate" class="block text-sm font-medium text-gray-700 mb-1">Daily Rate (KES)</label>
            <input type="number" id="daily_rate" step="0.01" name="daily_rate" value="<?= htmlspecialchars($equipment['daily_rate']) ?>" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
        </div>
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <input type="text" id="category" name="category" value="<?= htmlspecialchars($equipment['category']) ?>"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select id="status" name="status"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="available" <?= $equipment['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                <option value="rented" <?= $equipment['status'] === 'rented' ? 'selected' : '' ?>>Rented</option>
            </select>
        </div>
        <button type="submit" class="btn-primary w-full justify-center">
            Save Changes
        </button>
    </form>
</div>

<?php include_once "../includes/footer.php"; ?>
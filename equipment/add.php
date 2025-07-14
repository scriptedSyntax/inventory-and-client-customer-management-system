<?php
require_once "../config/db.php";
require_once "../includes/header.php";
require_once "../includes/functions.php"; // Ensure functions.php is included for insert() and flash()

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $type = trim($_POST['type']);
    $daily_rate = floatval($_POST['daily_rate']);
    $status = 'available'; // Default status for new equipment

    // Use insert helper
    $success = insert('equipment', [
        'name' => $name,
        'category' => $category,
        'type' => $type,
        'daily_rate' => $daily_rate,
        'status' => $status
    ]);

    if ($success) {
        flash("Equipment '{$name}' added successfully!", 'success');
        header("Location: ../dashboards/equipment.php"); // Redirect to the equipment dashboard
    } else {
        flash("Failed to add equipment. Please try again.", 'error');
        header("Location: add.php"); // Redirect back to add form on failure
    }
    exit;
}
?>

<div class="bg-white shadow-md rounded-lg p-6 mb-6 max-w-lg mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Equipment</h2>
    <form method="POST" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Equipment Name</label>
            <input type="text" id="name" name="name" placeholder="e.g., Arri Alexa Mini" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
        </div>
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <input type="text" id="type" name="type" placeholder="e.g., Camera, Light, Microphone" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
        </div>
        <div>
            <label for="daily_rate" class="block text-sm font-medium text-gray-700 mb-1">Daily Rate (KES)</label>
            <input type="number" id="daily_rate" step="0.01" name="daily_rate" placeholder="e.g., 5000.00" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
        </div>
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <input type="text" id="category" name="category" placeholder="e.g., Lighting, Camera, Audio" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
        </div>
        <button type="submit" class="btn-success w-full justify-center">
            Add Equipment
        </button>
    </form>
</div>

<?php include_once "../includes/footer.php"; ?>
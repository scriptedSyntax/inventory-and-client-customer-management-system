<?php
require_once "../config/db.php";
require_once "../includes/header.php"; // Updated header
require_once "../includes/functions.php"; // Ensure functions.php is included for fetchAll(), insert(), update(), and flash()

// Fetch clients & available equipment
$clients = fetchAll('clients');
$equipment = fetchAll('equipment', ['status' => 'available']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'];
    $equipment_id = $_POST['equipment_id'];
    $amount_due = floatval($_POST['amount_due']);
    $payment_status = trim($_POST['payment_status']); // 'Paid' or 'To Be Paid'

    // Insert rental
    $rental_success = insert('rentals', [
        'client_id' => $client_id,
        'equipment_id' => $equipment_id,
        'rental_date' => date('Y-m-d H:i:s'),
        'amount_due' => $amount_due,
        'payment_status' => $payment_status
    ]);

    if ($rental_success) {
        // Update equipment status only if rental was successfully created
        $equipment_update_success = update('equipment', ['status' => 'rented'], ['id' => $equipment_id]);

        if ($equipment_update_success) {
            flash("New rental created and equipment marked as rented successfully!", 'success');
        } else {
            // This is a partial failure, rental recorded but equipment status not updated.
            // Log this error in a real system.
            flash("Rental created, but failed to update equipment status. Please manually set equipment to 'rented'.", 'warning');
        }
        header("Location: list.php");
    } else {
        flash("Failed to create new rental. Please check inputs and try again.", 'error');
        header("Location: new.php");
    }
    exit;
}
?>

<div class="bg-white shadow-md rounded-lg p-6 mb-6 max-w-lg mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New Rental</h2>
    <form method="POST" class="space-y-4">
        <div>
            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
            <select id="client_id" name="client_id" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="">Select Client</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['client_name']) ?> (ID: <?= htmlspecialchars($client['client_id']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="equipment_id" class="block text-sm font-medium text-gray-700 mb-1">Equipment</label>
            <select id="equipment_id" name="equipment_id" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="">Select Available Equipment</option>
                <?php if (empty($equipment)): ?>
                    <option value="" disabled>No equipment available for rental.</option>
                <?php else: ?>
                    <?php foreach ($equipment as $item): ?>
                        <option value="<?= $item['id'] ?>"><?= htmlspecialchars($item['name']) ?> (KES <?= number_format($item['daily_rate'], 2) ?>/day)</option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <label for="amount_due" class="block text-sm font-medium text-gray-700 mb-1">Rental Amount (KES)</label>
            <input type="number" id="amount_due" name="amount_due" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                   placeholder="e.g., 15000.00" required step="0.01" />
        </div>

        <div>
            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
            <select id="payment_status" name="payment_status" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="To Be Paid">To Be Paid (Unpaid)</option>
                <option value="Paid">Paid</option>
            </select>
        </div>

        <button type="submit" class="btn-success w-full justify-center">
            Create Rental
        </button>
    </form>
</div>

<?php include_once "../includes/footer.php"; ?>
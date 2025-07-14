<?php
require_once "../config/db.php";
require_once "../includes/header.php";
require_once "../includes/functions.php"; // Ensure functions.php is included for fetchOne(), update() and flash()

$id = $_GET['id'] ?? 0;
$client = fetchOne('clients', ['id' => $id]);

if (!$client) {
    flash("Client not found.", 'error');
    header("Location: ../dashboards/clients.php");
    exit;
}

// Fetch guarantor details to pre-fill guarantor fields if applicable
$guarantor = fetchOne('guarantors', ['id' => $client['guarantor_id']]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = trim($_POST['client_name']);
    $client_phone = trim($_POST['client_phone']);
    $client_id_num = trim($_POST['client_id']);
    $guarantor_id_num = trim($_POST['guarantor_id_num']); // This is the guarantor's ID number, not DB ID
    $guarantor_name = trim($_POST['guarantor_name']);
    $guarantor_phone = trim($_POST['guarantor_phone']);

    // Update guarantor if necessary (or create if not exists)
    $guarantor_db_id = $client['guarantor_id']; // Start with existing guarantor ID

    if ($guarantor_db_id && $guarantor) {
        // Update existing guarantor
        update('guarantors', [
            'name' => $guarantor_name,
            'id_number' => $guarantor_id_num,
            'phone' => $guarantor_phone
        ], ['id' => $guarantor_db_id]);
    } else {
        // Create new guarantor if none existed or new guarantor ID is provided
        // This is a simplified approach. A more robust system would handle changing guarantors carefully.
        $new_guarantor_id = insert('guarantors', [
            'name' => $guarantor_name,
            'id_number' => $guarantor_id_num,
            'phone' => $guarantor_phone,
            'address' => 'N/A' // Placeholder
        ]);
        if ($new_guarantor_id) {
            $guarantor_db_id = $new_guarantor_id;
        } else {
            flash("Failed to update/create guarantor.", 'error');
            header("Location: edit.php?id={$id}");
            exit;
        }
    }


    $success = update('clients', [
        'client_name' => $client_name,
        'client_phone' => $client_phone,
        'client_id' => $client_id_num,
        'guarantor_id' => $guarantor_db_id // Store the actual guarantor primary key ID
    ], ['id' => $id]);

    if ($success) {
        flash("Client '{$client_name}' updated successfully.", 'success');
        header("Location: ../dashboards/clients.php");
    } else {
        flash("Failed to update client. Please try again.", 'error');
        header("Location: edit.php?id={$id}");
    }
    exit;
}
?>

<div class="bg-white shadow-md rounded-lg p-6 mb-6 max-w-lg mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Client: <?= htmlspecialchars($client['client_name']) ?></h2>
    <form method="POST" class="space-y-4">
        <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Client Details</h3>
        <div>
            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client ID Number</label>
            <input type="text" id="client_id" name="client_id" value="<?= htmlspecialchars($client['client_id']) ?>" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
        </div>
        <div>
            <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">Client Full Name</label>
            <input type="text" id="client_name" name="client_name" value="<?= htmlspecialchars($client['client_name']) ?>" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
        </div>
        <div>
            <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-1">Client Phone Number</label>
            <input type="text" id="client_phone" name="client_phone" value="<?= htmlspecialchars($client['client_phone']) ?>" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
        </div>

        <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4 pt-4">Guarantor Details</h3>
        <div>
            <label for="guarantor_id_num" class="block text-sm font-medium text-gray-700 mb-1">Guarantor ID Number</label>
            <input type="text" id="guarantor_id_num" name="guarantor_id_num" value="<?= htmlspecialchars($guarantor['id_number'] ?? '') ?>" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
        </div>
        <div>
            <label for="guarantor_name" class="block text-sm font-medium text-gray-700 mb-1">Guarantor Full Name</label>
            <input type="text" id="guarantor_name" name="guarantor_name" value="<?= htmlspecialchars($guarantor['name'] ?? '') ?>" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
        </div>
        <div>
            <label for="guarantor_phone" class="block text-sm font-medium text-gray-700 mb-1">Guarantor Phone Number</label>
            <input type="text" id="guarantor_phone" name="guarantor_phone" value="<?= htmlspecialchars($guarantor['phone'] ?? '') ?>" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
        </div>
        <button type="submit" class="btn-primary w-full justify-center">
            Update Client
        </button>
    </form>
</div>

<?php include_once "../includes/footer.php"; ?>
<?php
session_start();
require_once "../config/db.php";
require_once "../includes/header.php";
require_once "../includes/functions.php"; // Ensure functions.php is included for flash() and generateOTP()

$otpSent = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if OTP verification is being submitted
    if (isset($_POST['otp_input'])) {
        $entered_otp = trim($_POST['otp_input']);
        if (isset($_SESSION['otp']) && $entered_otp === $_SESSION['otp']) {
            // OTP is correct, save client data
            $client_data = $_SESSION['client_data'];

            // First, check if guarantor exists or create them
            $guarantor = fetchOne('guarantors', ['id_number' => $client_data['guarantor_id']]);
            $guarantor_id_db = null;
            if ($guarantor) {
                $guarantor_id_db = $guarantor['id'];
            } else {
                // If guarantor doesn't exist, create a placeholder.
                // In a real app, you'd have a separate form/process to add full guarantor details.
                $guarantor_id_db = insert('guarantors', [
                    'name' => 'Guarantor ' . $client_data['guarantor_id'], // Placeholder name
                    'id_number' => $client_data['guarantor_id'],
                    'phone' => $_SESSION['guarantor_phone_for_otp'], // Use the phone number from session
                    'address' => 'N/A' // Placeholder
                ]);
            }

            if ($guarantor_id_db) {
                $success = insert('clients', [
                    'client_id' => $client_data['client_id'],
                    'client_name' => $client_data['client_name'],
                    'client_phone' => $client_data['client_phone'],
                    'guarantor_id' => $guarantor_id_db // Store the actual guarantor primary key ID
                ]);

                if ($success) {
                    flash("Client '{$client_data['client_name']}' added successfully!", 'success');
                    unset($_SESSION['otp']);
                    unset($_SESSION['client_data']);
                    unset($_SESSION['guarantor_phone_for_otp']);
                    header("Location: ../dashboards/clients.php");
                    exit;
                } else {
                    flash("Failed to save client after OTP verification. Please try again.", 'error');
                }
            } else {
                flash("Failed to process guarantor information.", 'error');
            }
            header("Location: add.php"); // Redirect back to form or relevant page
            exit;

        } else {
            flash("Invalid or expired OTP. Please try again.", 'error');
            // If OTP is invalid, keep the client data in session so they can re-request OTP
            $otpSent = true; // Show OTP form again
        }
    } else { // Initial form submission (Send OTP)
        $client_id = trim($_POST['client_id']);
        $client_name = trim($_POST['client_name']);
        $client_phone = trim($_POST['client_phone']);
        $guarantor_id = trim($_POST['guarantor_id']); // This is ID number, not DB ID
        $guarantor_phone = trim($_POST['guarantor_phone']);

        // Basic validation
        if (empty($client_id) || empty($client_name) || empty($client_phone) || empty($guarantor_id) || empty($guarantor_phone)) {
            flash("All fields are required.", 'error');
        } else {
            // Check if client_id or client_phone already exists
            if (fetchOne('clients', ['client_id' => $client_id])) {
                flash("Client with this ID number already exists.", 'error');
            } elseif (fetchOne('clients', ['client_phone' => $client_phone])) {
                flash("Client with this phone number already exists.", 'error');
            } else {
                // Simulate sending OTP
                $otp = generateOTP(); // Assuming this is defined in functions.php
                $_SESSION['otp'] = $otp;
                $_SESSION['client_data'] = [
                    'client_id' => $client_id,
                    'client_name' => $client_name,
                    'client_phone' => $client_phone,
                    'guarantor_id' => $guarantor_id // Storing guarantor ID number temporarily
                ];
                $_SESSION['guarantor_phone_for_otp'] = $guarantor_phone; // Store for display

                // In real system: Send this OTP via SMS API
                // For demonstration, we just set a flag
                $otpSent = true;
                flash("OTP '{$otp}' sent to Guarantor's phone number ending in ...". substr($guarantor_phone, -4) .". Please enter it below.", 'info');
            }
        }
    }
}
?>

<div class="bg-white shadow-md rounded-lg p-6 mb-6 max-w-lg mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Client</h2>

    <?php if ($otpSent): ?>
        <form method="POST" action="add.php" class="space-y-4">
            <p class="text-gray-700 mb-3">
                Enter the OTP sent to Guarantor's phone number ending in **...<?= htmlspecialchars(substr($_SESSION['guarantor_phone_for_otp'] ?? '', -4)) ?>**:
                <span class="font-bold text-blue-600">(For demo: OTP is <?= htmlspecialchars($_SESSION['otp'] ?? 'N/A') ?>)</span>
            </p>
            <div>
                <label for="otp_input" class="block text-sm font-medium text-gray-700 mb-1">OTP</label>
                <input type="text" id="otp_input" name="otp_input" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Enter OTP" />
            </div>
            <button type="submit" class="btn-primary w-full justify-center">
                Verify & Save Client
            </button>
            <a href="add.php" class="text-center block text-sm text-blue-600 hover:text-blue-800 mt-2">Cancel / Start Over</a>
        </form>
    <?php else: ?>
        <form method="POST" class="space-y-4">
            <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client ID Number</label>
                <input type="text" id="client_id" name="client_id" placeholder="Client National ID / Passport" required
                       value="<?= htmlspecialchars($_SESSION['client_data']['client_id'] ?? '') ?>"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
            </div>
            <div>
                <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">Client Full Name</label>
                <input type="text" id="client_name" name="client_name" placeholder="Full Name" required
                       value="<?= htmlspecialchars($_SESSION['client_data']['client_name'] ?? '') ?>"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
            </div>
            <div>
                <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-1">Client Phone Number</label>
                <input type="text" id="client_phone" name="client_phone" placeholder="e.g., +254712345678" required
                       value="<?= htmlspecialchars($_SESSION['client_data']['client_phone'] ?? '') ?>"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
            </div>
            <div>
                <label for="guarantor_id" class="block text-sm font-medium text-gray-700 mb-1">Guarantor ID Number</label>
                <input type="text" id="guarantor_id" name="guarantor_id" placeholder="Guarantor National ID / Passport" required
                       value="<?= htmlspecialchars($_SESSION['client_data']['guarantor_id'] ?? '') ?>"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
            </div>
            <div>
                <label for="guarantor_phone" class="block text-sm font-medium text-gray-700 mb-1">Guarantor Phone Number</label>
                <input type="text" id="guarantor_phone" name="guarantor_phone" placeholder="e.g., +254712345678" required
                       value="<?= htmlspecialchars($_SESSION['guarantor_phone_for_otp'] ?? '') ?>"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
            </div>
            <button type="submit" class="btn-success w-full justify-center">
                Send OTP to Guarantor
            </button>
        </form>
    <?php endif; ?>
</div>

<?php include_once "../includes/footer.php"; ?>
<?php
include '../config/db.php'; // Assuming db.php handles PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $error = ''; // Initialize error message

    if (empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $error = "This email is already registered.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO admins (email, password) VALUES (?, ?)");
                if ($stmt->execute([$email, $hashed_password])) {
                    session_start();
                    $_SESSION['flash'] = "Account created successfully! Please login.";
                    header("Location: login.php");
                    exit;
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        } catch (PDOException $e) {
            // Log the error for debugging purposes
            error_log("Registration error: " . $e->getMessage());
            $error = "An unexpected error occurred during registration. Please try again later.";
        }
    }
    // If there's an error, store it in session flash to display after redirect or on current page
    if (!empty($error)) {
        session_start();
        $_SESSION['flash_error'] = $error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - Africa Grips</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary-dark-blue: #1A202C; /* Deep almost black for text/background */
            --accent-blue: #2B6CB0;      /* A strong blue for buttons/accents */
            --accent-blue-hover: #2C5282; /* Slightly darker blue for hover states */
            --soft-gray: #F7FAFC;        /* Off-white for body background */
            --card-background: #FFFFFF;  /* White for card backgrounds */
        }
        body {
            background-color: var(--soft-gray);
            font-family: 'Inter', sans-serif;
        }
        .btn-primary {
            background-color: var(--accent-blue);
            color: white;
            transition: background-color 0.2s ease-in-out;
        }
        .btn-primary:hover {
            background-color: var(--accent-blue-hover);
        }
        .form-input-focus:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 2px rgba(43, 108, 176, 0.5);
            outline: none;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen text-gray-800">

    <div class="bg-card-background p-8 rounded-lg shadow-xl w-full max-w-md">
        <h2 class="text-3xl font-extrabold text-center text-primary-dark-blue mb-6">
            Register New Admin
        </h2>

        <?php
        session_start(); // Ensure session is started to check for flash messages
        if (isset($_SESSION['flash_error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"><?php echo htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="your.email@example.com"
                    required
                    class="appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight form-input-focus transition duration-200 ease-in-out"
                >
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    class="appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight form-input-focus transition duration-200 ease-in-out"
                >
            </div>
            <div class="mb-6">
                <label for="confirm_password" class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="••••••••"
                    required
                    class="appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight form-input-focus transition duration-200 ease-in-out"
                >
            </div>
            <button
                type="submit"
                class="btn-primary w-full py-3 rounded-lg text-lg font-semibold hover:btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-blue"
            >
                Register
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">Already have an account?
                <a href="login.php" class="text-accent-blue hover:underline font-semibold">Login here</a>
            </p>
        </div>
    </div>

</body>
</html>
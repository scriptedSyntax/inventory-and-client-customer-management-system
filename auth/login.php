<?php
session_start();
include '../config/db.php'; // Assuming db.php handles PDO connection

$error = ''; // Initialize error message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
            $stmt->execute([$email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin'] = $admin['email'];
                $_SESSION['flash'] = "Welcome back, " . htmlspecialchars($admin['email']) . "!";
                header("Location: ../dashboards/admin.php");
                exit;
            } else {
                $error = "Invalid email or password. Please try again.";
            }
        } catch (PDOException $e) {
            // Log the error for debugging purposes (e.g., to a file)
            error_log("Login error: " . $e->getMessage());
            $error = "An unexpected error occurred. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Africa Grips</title>
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
            font-family: 'Inter', sans-serif; /* A modern, clean font */
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
            Admin Login
        </h2>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
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
            <div class="mb-6">
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
            <button
                type="submit"
                class="btn-primary w-full py-3 rounded-lg text-lg font-semibold hover:btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-blue"
            >
                Login
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">Don't have an account?
                <a href="register.php" class="text-accent-blue hover:underline font-semibold">Register here</a>
            </p>
        </div>
    </div>

</body>
</html>
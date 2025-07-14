<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/functions.php'; // Ensure this path is correct based on where header.php is included
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Africa Grips Equipment Rental</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for professional look, if needed */
        .active-nav-link {
            @apply bg-blue-600 text-white shadow-md;
        }
        .inactive-nav-link {
            @apply text-gray-700 hover:bg-gray-100 hover:text-gray-900;
        }
        /* Basic button styles for consistency, if not covered by a global Tailwind config */
        .btn-base {
            @apply px-4 py-2 rounded-md font-medium text-sm transition-colors duration-200;
        }
        .btn-primary {
            @apply btn-base bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
        }
        .btn-success {
            @apply btn-base bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500;
        }
        .btn-danger {
            @apply btn-base bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500;
        }
        .btn-outline {
             @apply btn-base border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500;
        }
        /* Styles for table badges - these are already Tailwind in the previous examples, just for completeness */
        .badge-success { @apply px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800; }
        .badge-warning { @apply px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800; }
        .badge-danger { @apply px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800; }
        .badge-info { @apply px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800; }


    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans leading-normal tracking-normal">

<nav class="bg-white shadow-md py-3 px-6 mb-6">
    <div class="container mx-auto flex items-center justify-between">
        <div class="flex items-center gap-2">
            <a href="/africa_project/dashboards/admin.php" class="py-2 px-4 rounded-md transition-colors duration-200 <?= isActive('admin.php') ? 'active-nav-link' : 'inactive-nav-link' ?>">Dashboard</a>
            <a href="/africa_project/dashboards/clients.php" class="py-2 px-4 rounded-md transition-colors duration-200 <?= isActive('clients.php') ? 'active-nav-link' : 'inactive-nav-link' ?>">Clients</a>
            <a href="/africa_project/dashboards/equipment.php" class="py-2 px-4 rounded-md transition-colors duration-200 <?= isActive('equipment.php') ? 'active-nav-link' : 'inactive-nav-link' ?>">Equipment</a>
            <a href="/africa_project/rentals/new.php" class="py-2 px-4 rounded-md transition-colors duration-200 <?= isActive('new.php') ? 'active-nav-link' : 'inactive-nav-link' ?>">Rent</a>
            <a href="/africa_project/dashboards/debts.php" class="py-2 px-4 rounded-md transition-colors duration-200 <?= isActive('debts.php') ? 'active-nav-link' : 'inactive-nav-link' ?>">Unpaid</a>
        </div>

        <div class="flex items-center">
            <?php if (isset($_SESSION['admin'])): ?>
                <a href="/africa_project/auth/logout.php" class="btn-outline">Logout</a>
            <?php else: ?>
                <a href="/africa_project/auth/login.php" class="btn-success">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container mx-auto px-4 mb-8">
    <?php flash(); // Display flash messages ?>
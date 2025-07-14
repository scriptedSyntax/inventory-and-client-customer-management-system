<?php

if (!function_exists('generateOTP')) {
    function generateOTP($length = 6) {
        return str_pad(rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('statusBadge')) {
    function statusBadge($status) {
        $status = strtolower($status);
        switch ($status) {
            case 'paid':
                return '<span class="badge bg-success">Paid</span>';
            case 'to be paid':
                return '<span class="badge bg-warning text-dark">Pending</span>';
            case 'overdue':
                return '<span class="badge bg-danger">Overdue</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }
}

if (!function_exists('isActive')) {
    function isActive($path) {
        return strpos($_SERVER['REQUEST_URI'], $path) !== false ? 'active' : '';
    }
}

if (!function_exists('flash')) {
    function flash() {
        if (isset($_SESSION['flash'])) {
            echo '<div class="alert alert-info text-sm p-2 mb-3 rounded">' . $_SESSION['flash'] . '</div>';
            unset($_SESSION['flash']);
        }
    }
}

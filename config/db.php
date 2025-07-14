<?php
// config/db.php

$host = 'localhost';
$db   = 'africa_grips';
$user = 'root';
$pass = ''; // replace with your password if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// ---------------------------
// GLOBAL CRUD FUNCTIONS
// ---------------------------
require_once '../config/db.php';

function insert($table, $data) {
    global $pdo;
    $keys = implode(",", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));
    $sql = "INSERT INTO $table ($keys) VALUES ($placeholders)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}

function update($table, $data, $where) {
    global $pdo;
    $fields = '';
    foreach ($data as $key => $value) {
        $fields .= "$key=:$key, ";
    }
    $fields = rtrim($fields, ', ');

    $whereClause = '';
    foreach ($where as $key => $value) {
        $whereClause .= "$key=:$key AND ";
    }
    $whereClause = rtrim($whereClause, ' AND ');

    $sql = "UPDATE $table SET $fields WHERE $whereClause";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(array_merge($data, $where));
}

function delete($table, $where) {
    global $pdo;
    $whereClause = '';
    foreach ($where as $key => $value) {
        $whereClause .= "$key=:$key AND ";
    }
    $whereClause = rtrim($whereClause, ' AND ');
    $sql = "DELETE FROM $table WHERE $whereClause";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($where);
}

function fetchAll($table, $conditions = []) {
    global $pdo;
    $sql = "SELECT * FROM $table";
    if (!empty($conditions)) {
        $whereClause = '';
        foreach ($conditions as $key => $value) {
            $whereClause .= "$key=:$key AND ";
        }
        $whereClause = rtrim($whereClause, ' AND ');
        $sql .= " WHERE $whereClause";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($conditions);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchOne($table, $conditions) {
    global $pdo;
    $sql = "SELECT * FROM $table WHERE ";
    $whereClause = '';
    foreach ($conditions as $key => $value) {
        $whereClause .= "$key=:$key AND ";
    }
    $whereClause = rtrim($whereClause, ' AND ');
    $sql .= $whereClause . " LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($conditions);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

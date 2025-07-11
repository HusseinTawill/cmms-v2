<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $admin_id = $_SESSION['admin_id'];

    if ($name && $phone && $email) {
        $stmt = $conn->prepare("INSERT INTO customers (full_name, phone, email, admin_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $phone, $email, $admin_id);

        if ($stmt->execute()) {
            header("Location: customers.php");
            exit();
        } else {
            echo "Error adding customer: " . $stmt->error;
        }
    } else {
        echo "All fields are required.";
    }
}
?>

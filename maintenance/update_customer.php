<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_POST['id'];
$name = $_POST['full_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$admin_id = $_SESSION['admin_id'];

// Only update if customer belongs to the logged-in admin
$stmt = $conn->prepare("UPDATE customers SET full_name=?, phone=?, email=? WHERE id=? AND admin_id=?");
$stmt->bind_param("sssii", $name, $phone, $email, $id, $admin_id);

if ($stmt->execute()) {
    header("Location: customers.php");
} else {
    echo "Update failed.";
}
?>

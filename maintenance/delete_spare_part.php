<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM spare_parts WHERE id = ? AND admin_id = ?");
    $stmt->bind_param("ii", $id, $admin_id);
    $stmt->execute();
}

header("Location: spare_parts.php?deleted=true");
exit();
?>

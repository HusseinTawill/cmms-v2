<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$customer_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ? AND admin_id = ?");
$stmt->bind_param("ii", $customer_id, $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if (!$customer) {
    echo "Customer not found or access denied.";
    exit();
}
?>

<form method="POST" action="update_customer.php">
  <input type="hidden" name="id" value="<?= $customer['id'] ?>">
  <input type="text" name="full_name" value="<?= htmlspecialchars($customer['full_name']) ?>" required>
  <input type="text" name="phone" value="<?= htmlspecialchars($customer['phone']) ?>" required>
  <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required>
  <button type="submit">Update</button>
</form>

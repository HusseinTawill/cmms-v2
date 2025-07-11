<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$employee_id = $_GET['id'] ?? null;

if (!$employee_id) {
    header("Location: employee.php");
    exit();
}

// Fetch employee
$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ? AND admin_id = ?");
$stmt->bind_param("ii", $employee_id, $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    echo "Employee not found.";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $role  = $_POST['role'];

    // Update basic info
    $stmt = $conn->prepare("UPDATE employees SET name = ?, email = ?, role = ? WHERE id = ? AND admin_id = ?");
    $stmt->bind_param("sssii", $name, $email, $role, $employee_id, $admin_id);
    $stmt->execute();

    // Handle optional password update
    $new_password = $_POST['new_password'] ?? '';
    $confirm      = $_POST['confirm_password'] ?? '';

    if (!empty($new_password)) {
        if ($new_password !== $confirm) {
            echo "❌ Passwords do not match!";
            exit();
        }

        if (!preg_match('/[a-zA-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
            echo "❌ Password must contain at least one letter and one number.";
            exit();
        }

        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE employees SET password = ? WHERE id = ? AND admin_id = ?");
        $stmt->bind_param("sii", $hashed, $employee_id, $admin_id);
        $stmt->execute();
    }

    header("Location: employee.php?updated=true");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Employee</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="modal show">
    <div class="modal-box">
      <h2>Edit Employee</h2>
      <form method="POST">
        <input type="text" name="name" value="<?= htmlspecialchars($employee['name']) ?>" placeholder="Full Name" required>
        <input type="email" name="email" value="<?= htmlspecialchars($employee['email']) ?>" placeholder="Email Address" required>
        <input type="text" name="role" value="<?= htmlspecialchars($employee['role']) ?>" placeholder="Role" required>
        <hr>
        <input type="password" name="new_password" placeholder="New Password (optional)">
        <input type="password" name="confirm_password" placeholder="Retype New Password">
        <div style="display: flex; justify-content: space-between;">
          <button type="submit" class="btn-update">Update</button>
          <a href="employee.php" class="btn-cancel">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>

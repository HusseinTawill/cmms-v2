<?php
require 'config.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $role = $_POST['role'];

  $admin_id = $_SESSION['admin_id'];
$stmt = $conn->prepare("INSERT INTO employees (name, email, role, admin_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $name, $email, $role, $admin_id);


  if ($stmt->execute()) {
    echo "Employee added successfully!";
  } else {
    echo "Error: " . $stmt->error;
  }
}
?>

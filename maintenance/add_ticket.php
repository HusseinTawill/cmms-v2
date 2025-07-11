<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = trim($_POST['customer_name']);
    $device_name = trim($_POST['device_name']);
    $serial = trim($_POST['serial_number']);
    $type = trim($_POST['type']);
    $problem = trim($_POST['problem_description']);
    $accessories = trim($_POST['accessories']);
    $assigned_employee_name = trim($_POST['assigned_employee']);
    $admin_id = $_SESSION['admin_id']; // ✅ this links ticket to current admin

    // Get Customer ID
    $stmt1 = $conn->prepare("SELECT id FROM customers WHERE full_name = ?");
    $stmt1->bind_param("s", $customer_name);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    if ($result1 && $result1->num_rows > 0) {
        $customer = $result1->fetch_assoc();
        $customer_id = $customer['id'];
    } else {
        $_SESSION['error'] = "Customer '$customer_name' not found.";
        header("Location: dashboard.php");
        exit();
    }

    // Get Employee ID
    $stmt2 = $conn->prepare("SELECT id FROM employees WHERE name = ?");
    $stmt2->bind_param("s", $assigned_employee_name);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result2 && $result2->num_rows > 0) {
        $employee = $result2->fetch_assoc();
        $employee_id = $employee['id'];
    } else {
        $_SESSION['error'] = "Employee '$assigned_employee_name' not found.";
        header("Location: dashboard.php");
        exit();
    }

    // Insert Ticket with admin_id
    $stmt = $conn->prepare("INSERT INTO tickets (customer_id, device_name, serial_number, type, problem_description, accessories, assigned_employee_id, admin_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssi", $customer_id, $device_name, $serial, $type, $problem, $accessories, $employee_id, $admin_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Ticket added successfully.";
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Error inserting ticket: " . $stmt->error;
        header("Location: dashboard.php");
        exit();
    }
}
?>

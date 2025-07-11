<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$ticket_id = $_GET['id'] ?? null;

if (!$ticket_id) {
    header("Location: dashboard.php");
    exit();
}

// Fetch ticket details (replace with your table/column names)
$stmt = $conn->prepare("SELECT customer_id, technician_id, problem, solution, cost, charged FROM tickets WHERE id = ? AND admin_id = ?");
$stmt->bind_param("ii", $ticket_id, $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

if ($ticket) {
    $profit = $ticket['charged'] - $ticket['cost'];

    // Insert into ticket_report
    $stmt = $conn->prepare("INSERT INTO ticket_report (ticket_id, admin_id, customer_id, technician_id, problem, solution, cost, charged, profit) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "iiisssddd",
        $ticket_id,
        $admin_id,
        $ticket['customer_id'],
        $ticket['technician_id'],
        $ticket['problem'],
        $ticket['solution'],
        $ticket['cost'],
        $ticket['charged'],
        $profit
    );
    $stmt->execute();

    // Update ticket status to Done
    $stmt = $conn->prepare("UPDATE tickets SET status = 'Done' WHERE id = ? AND admin_id = ?");
    $stmt->bind_param("ii", $ticket_id, $admin_id);
    $stmt->execute();
}

// Redirect to ticket report page
header("Location: ticket_report.php?ticket=$ticket_id&done=true");
exit();
?>

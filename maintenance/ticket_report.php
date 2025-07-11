<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

$stmt = $conn->prepare("SELECT tr.*, c.name AS customer_name, e.name AS technician_name FROM ticket_report tr
LEFT JOIN customers c ON tr.customer_id = c.id
LEFT JOIN employees e ON tr.technician_id = e.id
WHERE tr.admin_id = ? ORDER BY tr.created_at DESC");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$results = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ticket Report</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="logo"><?= htmlspecialchars($_SESSION['admin_company']) ?></div>
      <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="ticket_report.php">Ticket Report</a>
      </nav>
    </aside>

    <div class="main">
      <header class="main-header">
        <h2>Completed Ticket Reports</h2>
        <div class="user">
          <span>👤</span>
          <button class="logout"><a href="logout.php">Logout</a></button>
        </div>
      </header>

      <section class="content">
        <div class="content-box">
          <table>
            <thead>
              <tr>
                <th>Ticket ID</th>
                <th>Customer</th>
                <th>Technician</th>
                <th>Problem</th>
                <th>Solution</th>
                <th>Cost</th>
                <th>Charged</th>
                <th>Profit</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $results->fetch_assoc()): ?>
              <tr>
                <td><?= $row['ticket_id'] ?></td>
                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                <td><?= htmlspecialchars($row['technician_name']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['problem'])) ?></td>
                <td><?= nl2br(htmlspecialchars($row['solution'])) ?></td>
                <td>$<?= number_format($row['cost'], 2) ?></td>
                <td>$<?= number_format($row['charged'], 2) ?></td>
                <td>$<?= number_format($row['profit'], 2) ?></td>
                <td><?= $row['created_at'] ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>
</body>
</html>

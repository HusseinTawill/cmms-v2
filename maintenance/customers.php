<?php
session_start();

// Prevent browser from caching the page
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Block access if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title>Maintenance Dashboard</title>
  <link rel="stylesheet" href="css/styles.css"/>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
    <div class="logo"><?= htmlspecialchars($_SESSION['admin_company']) ?></div> 
      <nav>
        <a href="dashboard.php">Dashboard</a>
        <div class="dropdown">
          <button class="dropbtn">Customers ▾</button>
          <div class="dropdown-content">
            <a href="#" id="openAddCustomer">Add Customer</a>
            <a href="#" id="openSearchCustomer">Search Customer</a>
          </div>
        </div>
        <div class="dropdown">
          <button class="dropbtn">Tickets ▾</button>
          <div class="dropdown-content">
            <a href="#" id="openAddTicket">Add Ticket</a>
            <a href="#" id="openSearchTicket">Search Ticket</a>
          </div>
        </div>
        <div class="dropdown">
  <button class="dropbtn">Employees ▾</button>
  <div class="dropdown-content">
    <a href="#" id="openAddEmployee">Add Employee</a>
    <a href="#" id="openSearchEmployee">Search Employee</a>
  </div>
</div>

      </nav>
    </aside>

    <div class="main">
      <header class="main-header">
        <input type="text" class="search" placeholder="Search..." />
        <div class="user">
          <span>👤</span>
          <button class="logout"> <a href="logout.php">Logout</a></button>
        </div>
      </header>

        <div class="modal hidden" id="addCustomerModal">
    <div class="modal-box">
      <button class="modal-close" onclick="closeModal('addCustomerModal')">✕</button>
      <h2>Add Customer</h2>
      <form class="modal-form" action="add_customer.php" method="POST">
  <input type="text" name="full_name" placeholder="Full Name" required />
  <input type="tel" name="phone" placeholder="Phone Number" required />
  <input type="email" name="email" placeholder="Email Address" required />
  <button type="submit">Add</button>
</form>

    </div>
  </div>

      <section class="content">
  <div class="content-box">
    <h2>My Customers</h2>
    <table>
      <thead>
        <tr>
          <th>Full Name</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        require 'config.php';
        $admin_id = $_SESSION['admin_id'];

        $stmt = $conn->prepare("SELECT id, full_name, phone, email FROM customers WHERE admin_id = ?");
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
          echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
          echo "<td>" . htmlspecialchars($row['email']) . "</td>";
          echo "<td><a href='edit_customer.php?id=" . $row['id'] . "' class='edit-btn'>Edit</a></td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</section>

 <script src="js/script.js"></script>
<?php
session_start();

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
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
        <a href="#">Dashboard</a>
        <div class="dropdown">
          <button class="dropbtn">Customers ▾</button>
          <div class="dropdown-content">
            <a href="customers.php" id="">View Customer</a>
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
    <a href="employee.php" >View Employee</a>
    <a href="#" id="openAddEmployee">Add Employee</a>
    <a href="#" id="openSearchEmployee">Search Employee</a>
  </div>
</div>

<a href="spare_parts.php">Spare Parts</a>
<a href="ticket_report.php">Ticket Report</a>

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

      <section class="content">
        <div class="content-box">
          <h2>Tickets</h2>
          <table>
            <thead>
              <tr>
                <th>Customer</th>
                <th>Problem</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
<tbody>
<?php
require 'config.php';
$admin_id = $_SESSION['admin_id']; // 🧠 Only fetch tickets for this admin

$stmt = $conn->prepare("
    SELECT 
        t.id, 
        c.full_name AS customer_name, 
        t.problem_description, 
        t.status 
    FROM tickets t
    JOIN customers c ON t.customer_id = c.id
    WHERE t.admin_id = ?
    ORDER BY t.id DESC
");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['problem_description']) . "</td>";
    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
    echo "<td><button onclick=\"openViewTicket('" . htmlspecialchars($row['problem_description']) . "')\">View</button></td>";
    echo "</tr>";
}
?>
</tbody>


          </table>
        </div>
      </section>
    </div>
  </div>

  <!-- Modals -->
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

  <div class="modal hidden" id="searchCustomerModal">
    <div class="modal-box">
      <button class="modal-close" onclick="closeModal('searchCustomerModal')">✕</button>
      <h2>Search Customer</h2>
      <input type="text" id="searchInput" placeholder="Enter customer name..." />
      <ul class="search-results" id="searchResults"></ul>
    </div>
  </div>

  <div class="modal hidden" id="addTicketModal">
    <div class="modal-box">
      <button class="modal-close" onclick="closeModal('addTicketModal')">✕</button>
      <h2>Add Ticket</h2>
      <form class="modal-form" action="add_ticket.php" method="POST">
  <input type="text" name="customer_name" placeholder="Customer Name" required />
  <input type="text" name="device_name" placeholder="Device Name" required />
  <input type="text" name="serial_number" placeholder="Serial Number" required />
  <select name="type" required>
    <option value="">Select Type</option>
    <option value="software">Software</option>
    <option value="hardware">Hardware</option>
  </select>
  <input type="text" name="problem_description" placeholder="Problem Description" required />
  <input type="text" name="accessories" placeholder="Accessories (if any)" />
  <input type="text" name="assigned_employee" placeholder="Assign to Employee (ID)" required />
  <div class="actions">
    <button type="submit" class="add-btn">Add</button>
    <button type="button" class="print-btn" onclick="window.print()">Print</button>
  </div>
</form>
    </div>
  </div>

  <div class="modal hidden" id="searchTicketModal">
    <div class="modal-box">
      <button class="modal-close" onclick="closeModal('searchTicketModal')">✕</button>
      <h2>Search Tickets</h2>
      <input type="text" id="ticketSearchInput" placeholder="Enter ticket or customer name..." />
      <ul class="search-results" id="ticketSearchResults"></ul>
    </div>
  </div>

  <div class="modal hidden" id="addEmployeeModal">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal('addEmployeeModal')">✕</button>
    <h2>Add Employee</h2>
    <form class="modal-form" action="add_employee.php" method="POST">
      <input type="text" name="name" placeholder="Full Name" required />
      <input type="email" name="email" placeholder="Email Address" required />
      <input type="text" name="role" placeholder="Role (e.g., Technician)" required />
      <button type="submit">Add</button>
    </form>
  </div>
</div>

  <div class="modal hidden" id="viewTicketModal">
    <div class="modal-box">
      <button class="modal-close" onclick="closeModal('viewTicketModal')">✕</button>
      <h2>View Ticket</h2>
      <form action="save_solution.php" method="POST" class="modal-form">
  <label>Problem</label>
  <textarea name="problem_description" id="problemText" readonly></textarea>

  <label>Solution</label>
  <textarea name="solution" required></textarea>

  <label>Actual Price</label>
  <input type="number" placeholder="Actual Price" required />

  <label>Seller Price</label>
  <input type="number" placeholder="Seller Price" required />

  <div class="actions">
    <button type="submit" class="add-btn">Submit</button>
    <button type="button" class="mark-done">Mark as Done</button>
    <button type="button" class="notify-btn">Send Message</button>
  </div>
</form>

    </div>
  </div>

  <!-- Script -->
 <script src="js/script.js"></script>
</body>
</html>

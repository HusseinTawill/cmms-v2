<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Handle form submission to add new employee
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'], $_POST['email'], $_POST['role'], $_POST['password'], $_POST['confirm_password'])) {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $role     = $_POST['role'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if ($password !== $confirm) {
        echo "<script>alert('❌ Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    if (!preg_match('/[a-zA-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        echo "<script>alert('❌ Password must contain at least one letter and one number.'); window.history.back();</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO employees (admin_id, name, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $admin_id, $name, $email, $hashed_password, $role);
    $stmt->execute();

    header("Location: employee.php?added=true");
    exit();
}

// Fetch employees belonging to this admin
$stmt = $conn->prepare("SELECT id, name, email, password, role FROM employees WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
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
  <style>
    input:invalid {
      border: 2px solid red;
    }
    input:valid {
      border: 2px solid green;
    }
    .validation-message {
      font-size: 13px;
      color: red;
      margin-top: -10px;
      margin-bottom: 10px;
      display: none;
    }
    .validation-message.active {
      display: block;
    }
  </style>
  <script>
    function validatePasswordMatch() {
      const pass = document.getElementById('password');
      const confirm = document.getElementById('confirm_password');
      const message = document.getElementById('password_message');

      if (pass.value !== confirm.value) {
        confirm.setCustomValidity("Passwords do not match.");
        message.textContent = "❌ Passwords do not match.";
        message.classList.add("active");
      } else if (!(/[a-zA-Z]/.test(pass.value) && /[0-9]/.test(pass.value))) {
        confirm.setCustomValidity("Password must include letters and numbers.");
        message.textContent = "❌ Password must include letters and numbers.";
        message.classList.add("active");
      } else {
        confirm.setCustomValidity("");
        message.textContent = "";
        message.classList.remove("active");
      }
    }
  </script>
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

      <section class="content">
        <div class="content-box">
          <h2>My Employees</h2>
          <table>
            <thead>
              <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Role</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['name']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= htmlspecialchars($row['password']) ?></td>
                  <td><?= htmlspecialchars($row['role']) ?></td>
                  <td><a href="edit_employee.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>

  <div class="modal hidden" id="addEmployeeModal">
    <div class="modal-box">
      <button class="modal-close" onclick="closeModal('addEmployeeModal')">✕</button>
      <h2>Add Employee</h2>
      <form class="modal-form" method="POST">
        <input type="text" name="name" placeholder="Full Name" required />
        <input type="email" name="email" placeholder="Email Address" required />
        <input type="text" name="role" placeholder="Role (e.g., Technician)" required />
        <input type="password" id="password" name="password" placeholder="Password (letters & numbers)" required pattern="(?=.*[a-zA-Z])(?=.*[0-9]).{6,}" oninput="validatePasswordMatch()" />
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Retype Password" required oninput="validatePasswordMatch()" />
        <div id="password_message" class="validation-message"></div>
        <button type="submit" id="submitBtn">Add</button>
      </form>
    </div>
  </div>

  <script src="js/script.js"></script>
</body>
</html>

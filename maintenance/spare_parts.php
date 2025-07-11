<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_id = $_SESSION['admin_id'];

$id = $_GET['id'] ?? null;
$part = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM spare_parts WHERE id = ? AND admin_id = ?");
    $stmt->bind_param("ii", $id, $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $part = $result->fetch_assoc() ?: null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_part'])) {
    $source = $_POST['source'];
    $name = $_POST['name'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("UPDATE spare_parts SET source = ?, name = ?, model = ?, price = ?, quantity = ? WHERE id = ? AND admin_id = ?");
    $stmt->bind_param("sssdiii", $source, $name, $model, $price, $quantity, $id, $admin_id);
    $stmt->execute();

    header("Location: spare_parts.php?updated=true");
    exit();
}

// Handle new part addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_part'])) {
    $source = $_POST['source'];
    $name = $_POST['name'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO spare_parts (admin_id, source, name, model, price, quantity) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssdi", $admin_id, $source, $name, $model, $price, $quantity);
    $stmt->execute();

    header("Location: spare_parts.php?added=true");
    exit();
}

// Fetch all spare parts for table display
$search = $_GET['search'] ?? '';
$search_param = "%$search%";
$stmt = $conn->prepare("SELECT * FROM spare_parts WHERE admin_id = ? AND (name LIKE ? OR model LIKE ?)");
$stmt->bind_param("iss", $admin_id, $search_param, $search_param);
$stmt->execute();
$all_parts = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Spare Parts</title>
  <link rel="stylesheet" href="css/styles.css">
  <script>
    function closeModal(id) {
      document.getElementById(id).classList.add('hidden');
    }
    function openModal(id) {
      document.getElementById(id).classList.remove('hidden');
    }
  </script>
</head>
<body <?php if ($id && $part): ?>onload="openModal('editSparePartModal')"<?php endif; ?>>
  <div class="layout">
    <aside class="sidebar">
      <div class="logo"><?= htmlspecialchars($_SESSION['admin_company']) ?></div>
      <nav>
        <a href="dashboard.php">Dashboard</a>
        <div class="dropdown">
          <button class="dropbtn">Spare Parts</button>
        </div>
      </nav>
    </aside>

    <div class="main">
      <header class="main-header">
        <div style="display: flex; gap: 10px; align-items: center; width: 100%;">
          <form method="GET" style="flex: 1;">
            <input type="text" class="search" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>" />
          </form>
          <button class="btn-add" onclick="openModal('addSparePartModal')">+ Add Spare Part</button>
        </div>
        <div class="user">
          <span>👤</span>
          <button class="logout"><a href="logout.php">Logout</a></button>
        </div>
      </header>

      <section class="content">
        <div class="content-box">
          <h2>My Spare Parts</h2>
          <table>
            <thead>
              <tr>
                <th>Source</th>
                <th>Name</th>
                <th>Model</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $all_parts->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['source']) ?></td>
                  <td><?= htmlspecialchars($row['name']) ?></td>
                  <td><?= htmlspecialchars($row['model']) ?></td>
                  <td><?= htmlspecialchars($row['price']) ?></td>
                  <td><?= htmlspecialchars($row['quantity']) ?></td>
                  <td><a href="?id=<?= $row['id'] ?>" class="edit-btn">Edit</a></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>

  <?php if ($id && $part): ?>
  <!-- Edit Spare Part Modal -->
  <div class="modal" id="editSparePartModal">
    <div class="modal-box">
      <button class="modal-close" onclick="closeModal('editSparePartModal')">✕</button>
      <h2>Edit Spare Part</h2>
      <form class="modal-form" method="POST">
        <input type="text" name="source" value="<?php echo htmlspecialchars($part['source']); ?>" placeholder="Source" required>
        <input type="text" name="name" value="<?php echo htmlspecialchars($part['name']); ?>" placeholder="Part Name" required>
        <input type="text" name="model" value="<?php echo htmlspecialchars($part['model']); ?>" placeholder="Model Number" required>
        <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($part['price']); ?>" placeholder="Actual Price" required>
        <input type="number" name="quantity" value="<?php echo htmlspecialchars($part['quantity']); ?>" placeholder="Quantity" required>
        <div style="display: flex; justify-content: space-between;">
          <button type="submit" name="update_part" class="btn-update">Update</button>
          <a href="delete_spare_part.php?id=<?php echo $part['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this part?')">Delete</a>
        </div>
      </form>
    </div>
  </div>
  <?php endif; ?>

  <!-- Add Spare Part Modal -->
  <div class="modal hidden" id="addSparePartModal">
    <div class="modal-box">
      <button class="modal-close" onclick="closeModal('addSparePartModal')">✕</button>
      <h2>Add Spare Part</h2>
      <form class="modal-form" method="POST">
        <input type="text" name="source" placeholder="Source" required />
        <input type="text" name="name" placeholder="Part Name" required />
        <input type="text" name="model" placeholder="Model Number" required />
        <input type="number" step="0.01" name="price" placeholder="Actual Price" required />
        <input type="number" name="quantity" placeholder="Quantity" required />
        <button type="submit" name="add_part">Add</button>
      </form>
    </div>
  </div>

  <script src="js/script.js"></script>
</body>
</html>

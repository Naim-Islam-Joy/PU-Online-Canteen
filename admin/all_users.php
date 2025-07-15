<?php
session_start();
include 'db.php';

// 🔐 নিরাপত্তা চেক
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
   eader("Location: admin_login.php");
   exit;
}

// সব ইউজার বের করা
$sql = "SELECT id, full_name, phone, email, balance FROM users ORDER BY id DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>All Users - PU Canteen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f2f2f2;
      font-family: 'Noto Sans Bengali', sans-serif;
    }

    .sidebar {
      height: 100vh;
      background-color: #343a40;
      color: white;
      padding-top: 30px;
      position: fixed;
      width: 220px;
    }

    .sidebar h4 {
      text-align: center;
      margin-bottom: 30px;
    }

    .sidebar a {
      display: block;
      color: white;
      padding: 12px 20px;
      text-decoration: none;
    }

    .sidebar a:hover {
      background-color: #495057;
    }

    .main-content {
      margin-left: 230px;
      padding: 30px;
    }

    @media (max-width: 768px) {
      .sidebar {
        position: relative;
        width: 100%;
        height: auto;
      }

      .main-content {
        margin-left: 0;
        padding: 15px;
      }
    }

    .table thead {
      background-color: #343a40;
      color: white;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
   <h4>📊 Admin Panel</h4>
    <a href="index.php">🏠 Dashboard</a>
	<a href="manage_orders.php">🧾 Manage Orders</a>
    <a href="admin_orders.php">📦 All Orders</a>
    <a href="add_product.php">➕ Add Product</a>
    <a href="all_users.php">👤 All Users</a>
	<a href="admin_balance_requests.php">💳 Balance Requests</a>
    <a href="report.php">🕒 Pending Reports</a>
    <a href="confirm_report.php">✅ Confirm Reports</a>
    <a href="admin_logout.php">🚪 Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h3 class="mb-4">🧑‍💼 Users List</h3>

    <?php if ($result->num_rows > 1): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
			  <th>Phone</th>
			   <th>Balance</th>
			  <th>Status</th>
            </tr>
          </thead>
          <tbody>
  <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= htmlspecialchars($row['full_name']) ?></td>
      <td><?= htmlspecialchars($row['email']) ?></td>
	  <td><?= htmlspecialchars($row['phone']) ?></td>
	  <td><?= htmlspecialchars($row['balance']) ?></td>
      <td>
        <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
        <a href="view_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">👁️ View</a>
        <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? delete this user')">🗑️ Delete</a>
      </td>
    </tr>
  <?php endwhile; ?>
</tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info">⚠️ User not found</div>
    <?php endif; ?>

  </div>
</body>
</html>

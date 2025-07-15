<?php
session_start();
include 'db.php';

// 🔐 নিরাপত্তা চেক
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.html");
    exit;
}

// Query Counts
$manage_orders = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders"));
$total_orders = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders"));
$pending_orders = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders WHERE order_status = 1"));
$confirmed_orders = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders WHERE order_status = 2"));
$total_products = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM product"));
$balance_requests = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users_balance_requests"));
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - PU Canteen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

    .card {
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .card h5 {
      font-size: 18px;
    }

    .card p {
      font-size: 24px;
      font-weight: bold;
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
    <h2 class="mb-4">🏠 Dashboard</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card p-4 bg-white">
          <h5>🧾 Total Orders</h5>
          <p><?= $total_orders ?></p>
        </div>
      </div>
	  
	   <div class="col-md-4">
        <div class="card p-4 bg-white">
          <h5>📦 Manage Order</h5>
          <p><?= $manage_orders ?></p>
        </div>
      </div>
	  
      <div class="col-md-4">
        <div class="card p-4 bg-white">
          <h5>⏳ Pending Orders</h5>
          <p><?= $pending_orders ?></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 bg-white">
          <h5>✅ Confirmed Orders</h5>
          <p><?= $confirmed_orders ?></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 bg-white">
          <h5>🛍️ Total Products</h5>
          <p><?= $total_products ?></p>
        </div>
      </div>
	  
	  <div class="col-md-4">
        <div class="card p-4 bg-white">
          <h5>💳 Balance Requests</h5>
          <p><?= $balance_requests ?></p>
        </div>
      </div>
	  
      <div class="col-md-4">
        <div class="card p-4 bg-white">
          <h5>👥 Registered Users</h5>
          <p><?= $total_users ?></p>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

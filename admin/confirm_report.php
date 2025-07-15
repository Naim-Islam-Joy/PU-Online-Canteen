<?php
session_start();
include 'db.php';

// 🔐 Admin login check
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>Confirm Orders Report - PU Canteen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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
    <h2 class="mb-4">✅ Confirmed Orders</h2>

    <table class="table table-bordered table-striped bg-white">
      <thead class="table-dark">
        <tr>
          <th>Sl</th>
          <th>Order ID</th>
          <th>Order Items</th>
          <th>Total Price (৳)</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM orders WHERE order_status = 2";
        $qu = mysqli_query($conn, $sql);
        $i = 1;
        while ($row = mysqli_fetch_array($qu)) {
            ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= htmlspecialchars($row['product_id']) ?></td>
                <td><pre><?= htmlspecialchars($row['order_name']) ?></pre></td>
                <td><?= htmlspecialchars($row['total']) ?></td>
            </tr>
            <?php
            $i++;
        }
        ?>
      </tbody>
    </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>
</html>

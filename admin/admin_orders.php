<?php
include 'db.php';
$result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>অ্যাডমিন প্যানেল - PU Canteen</title>
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

    .card {
      border-radius: 10px;
    }

    .order-header {
      background-color: #343a40;
      color: white;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      padding: 15px;
    }

    .item-list li {
      font-size: 15px;
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
  <h2 class="mb-4">📦 All Orders</h2>

  <table class="table table-bordered table-striped bg-white">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>User ID</th>
        <th>Order Items</th>
        <th>Total (৳)</th>
        <th>Order Time</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['user_id'] ?></td>
          <td><pre class="mb-0"><?= htmlspecialchars($row['order_name']) ?></pre></td>
          <td>৳<?= htmlspecialchars($row['total']) ?></td>
          <td><?= htmlspecialchars($row['created_at']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

      
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    
    function OnStatusChanges(id,orders_id)
    {
        $.ajax({
          url:"change_status_pages.php",
          method:"POST",
          data:{id:id,orders_id:orders_id},
          success:function(resp)
          {
              console.log(resp)
          }
        })
    }
  </script>
</body>
</html>

<?php
include 'db.php';
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Approve Top-Up Request
if (isset($_GET['approve_id'])) {
    $id = (int)$_GET['approve_id'];

    $request_result = mysqli_query($conn, "SELECT * FROM users_balance_requests WHERE id = $id");
    $request = mysqli_fetch_assoc($request_result);

    if ($request && $request['status'] === 'pending') {
        $user_id = $request['user_id'];
        $amount = $request['amount'];

        $update_balance = mysqli_query($conn, "UPDATE users SET balance = balance + $amount WHERE id = $user_id");
        $update_status = mysqli_query($conn, "UPDATE users_balance_requests SET status = 'approved' WHERE id = $id");

        $_SESSION['msg'] = ($update_balance && $update_status)
            ? "✅ Approved & Balance Updated."
            : "❌ Database update failed.";
    } else {
        $_SESSION['msg'] = "❌ Invalid or already approved request.";
    }

    header("Location: admin_balance_requests.php");
    exit;
}

// Reject Top-Up Request
if (isset($_GET['reject_id'])) {
    $id = (int)$_GET['reject_id'];

    $request_result = mysqli_query($conn, "SELECT * FROM users_balance_requests WHERE id = $id");
    $request = mysqli_fetch_assoc($request_result);

    if ($request && $request['status'] === 'pending') {
        $update_status = mysqli_query($conn, "UPDATE users_balance_requests SET status = 'rejected' WHERE id = $id");

        $_SESSION['msg'] = $update_status
            ? "❌ Request Rejected."
            : "⚠️ Failed to update request status.";
    } else {
        $_SESSION['msg'] = "⚠️ Invalid or already processed request.";
    }

    header("Location: admin_balance_requests.php");
    exit;
}


// Fetch requests
$result = mysqli_query($conn, "
  SELECT r.*, u.full_name, u.email 
  FROM users_balance_requests r 
  JOIN users u ON r.user_id = u.id 
  ORDER BY r.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Balance Requests - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
      border-radius: 5px;
    }
    .content {
      flex: 200px;
      padding: 30px;
	  margin-left: 220px;
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
<div class="content">
  <h3 class="mb-4">🧾 Balance Top-Up Requests</h3>

  <?php if (isset($_SESSION['msg'])): ?>
    <div class="alert alert-info"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
  <?php endif; ?>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>User</th>
        <th>Email</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['full_name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td>৳<?= $row['amount'] ?></td>
        <td>
          <?php
            if ($row['status'] === 'pending') echo '<span class="badge bg-warning">Pending</span>';
            elseif ($row['status'] === 'approved') echo '<span class="badge bg-success">Approved</span>';
            else echo '<span class="badge bg-danger">Rejected</span>';
          ?>
        </td>
        <td>
          <?php if ($row['status'] === 'pending'): ?>
            <a href="?approve_id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Approve</a>
            <a href="?reject_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Reject</a>
          <?php else: ?>
            <button class="btn btn-secondary btn-sm" disabled>Done</button>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body>
</html>

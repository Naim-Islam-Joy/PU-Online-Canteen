<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];


// ইউজারের তথ্য আনা
$user_query = mysqli_query($conn, "SELECT full_name, email, balance FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($user_query);

$full_name = $user['full_name'];
$email = $user['email'];
$balance = $user['balance'];

// Pending amount আনুন
$pending_query = mysqli_query($conn, "SELECT SUM(amount) AS pending_amount FROM users_balance_requests WHERE user_id = '$user_id' AND status = 'pending'");
$pending_row = mysqli_fetch_assoc($pending_query);
$pending_amount = $pending_row['pending_amount'] ?? 0;

// সাময়িক ব্যালেন্স (display করার জন্য)
$display_balance = $balance + $pending_amount;


// অর্ডারের তথ্য আনা
//$order_result = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC");
$order_result = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC");


// ব্যালেন্স রিকোয়েস্ট সাবমিট
if (isset($_POST['request_balance'])) {
    $amount = (int)$_POST['amount'];
    if ($amount > 0) {
        $insert = mysqli_query($conn, "INSERT INTO users_balance_requests (user_id, amount, status) VALUES ('$user_id', '$amount', 'pending')");
        $msg = "✅ আপনার টপ-আপ রিকোয়েস্ট পাঠানো হয়েছে। অনুগ্রহ করে অনুমোদনের জন্য অপেক্ষা করুন।";
    } else {
        $msg = "❌ সঠিক পরিমাণ লিখুন।";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h2 class="mb-4">👤 My Profile</h2>

  <!-- ইউজারের তথ্য -->
  <div class="card p-3 mb-4">
    <h4>👤 Name: <span class="text-primary"><?php echo htmlspecialchars($full_name); ?></span></h4>
    <h5>📧 Email: <span class="text-secondary"><?php echo htmlspecialchars($email); ?></span></h5>
    <h4>💳 Current Balance (Including Pending): 
  <span class="text-success">৳<?= number_format($display_balance, 2) ?></span>
</h4>
<small class="text-muted">
  Approved: ৳<?= number_format($balance, 2) ?> |
  Pending: ৳<?= number_format($pending_amount, 2) ?>
</small>


  

    <hr>
    <h4>➕ Request Balance Top-Up</h4>
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="number" name="amount" class="form-control" placeholder="Enter amount (e.g. 100)" required>
        </div>
        <div class="col-md-2">
            <button name="request_balance" class="btn btn-primary">Request</button>
        </div>
    </form>
	
  </div>

  <!-- অর্ডারের তালিকা -->
  <div class="card p-3">
    <h4>🧾 My Orders</h4>
    <table class="table table-bordered mt-3">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Items</th>
          <th>Total</th>
          <th>Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
         <?php while ($row = mysqli_fetch_assoc($order_result)): ?>
      <?php
        $status = (int)$row['order_status'];
        $status_label = '<span class="badge bg-secondary">Unknown</span>';
        if ($status === 1) {
            $status_label = '<span class="badge bg-warning text-dark">Pending</span>';
        } elseif ($status === 2) {
            $status_label = '<span class="badge bg-success">Confirmed</span>';
        }
      ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['order_name']); ?></td>
            <td>৳<?php echo $row['total']; ?></td>
            <td><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
            <td><?php echo $status_label; ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <a href="index.php" class="btn btn-secondary mt-3">⬅️ Back to Home</a>
</div>

</body>
</html>

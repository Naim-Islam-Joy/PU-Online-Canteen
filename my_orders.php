<?php
session_start();
include 'db.php';

// ইউজার লগইন না করলে login.html এ পাঠিয়ে দাও
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Unknown User'; // যদি ভুলবশত নাম না থাকে

// অর্ডার বের করো
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders - PU Canteen</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
  <h2 class="text-center mb-4">📋 My Orders</h2>

  <!-- 🔵 ইউজারের নাম দেখাও -->
  <p class="text-center text-muted mb-4">
    Logged in as: <strong><?= htmlspecialchars($user_name) ?></strong>
  </p>

  <!-- 🔽 অর্ডার লিস্ট -->
  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="card mb-3">
        <div class="card-header">
          <strong>Order ID:</strong> <?= $row['id'] ?> |
          <strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($row['created_at'])) ?> |
          <strong>Total:</strong> ৳<?= number_format($row['total'], 2) ?>
        </div>
        <ul class="list-group list-group-flush">
          <?php
            $items = json_decode($row['items'], true);
            if (is_array($items)) {
              foreach ($items as $item):
          ?>
            <li class="list-group-item">
              <?= htmlspecialchars($item['name']) ?> - ৳<?= number_format($item['price'], 2) ?>
            </li>
          <?php
              endforeach;
            } else {
              echo "<li class='list-group-item text-danger'>❌ Item data not readable</li>";
            }
          ?>
        </ul>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="text-center text-muted">No orders found.</p>
  <?php endif; ?>

  <div class="text-center mt-3">
    <a href="index.html" class="btn btn-primary">⬅️ Back to Menu</a>
    <a href="logout.php" class="btn btn-danger ml-2">🚪 Logout</a>
  </div>
</div>
</body>
</html>

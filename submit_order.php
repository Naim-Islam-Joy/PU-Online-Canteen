<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];

  // Validate and decode order data
  if (isset($_POST['order_data']) && !empty($_POST['order_data'])) {
    $order_data = json_decode($_POST['order_data'], true);

    if (is_array($order_data) && count($order_data) > 0) {
      $total = array_sum(array_column($order_data, 'price'));
      $items_json = json_encode($order_data);

      // ইউজারের বর্তমান balance আনুন
      $balance_query = mysqli_query($conn, "SELECT balance FROM users WHERE id = $user_id");
      $user = mysqli_fetch_assoc($balance_query);
      $current_balance = $user['balance'];

      if ($current_balance >= $total) {
          // ✅ যথেষ্ট টাকা আছে
          $conn->begin_transaction();

          try {
              // 1. Order Insert
              $stmt = $conn->prepare("INSERT INTO orders (user_id, items, total) VALUES (?, ?, ?)");
              $stmt->bind_param("isi", $user_id, $items_json, $total);
              $stmt->execute();

              // 2. Wallet থেকে টাকা কাটা
              $update_stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
              $update_stmt->bind_param("di", $total, $user_id);
              $update_stmt->execute();

              $conn->commit();

              echo "<h2>✅ Order Successful. ৳$total কাটা হয়েছে।</h2>";
              echo "<script>localStorage.removeItem('cart');</script>";
              echo "<a href='index.html'>⬅️ Back to Menu</a> | ";
              echo "<a href='my_orders.php'>View My Orders</a>";
          } catch (Exception $e) {
              $conn->rollback();
              echo "❌ Order Failed. Error: " . $e->getMessage();
          }
      } else {
          echo "❌ পর্যাপ্ত ব্যালেন্স নেই। আপনার ব্যালেন্স: ৳" . number_format($current_balance, 2);
      }
    } else {
      echo "❌ Invalid order data.";
    }
  } else {
    echo "❌ No order data received.";
  }
}
?>

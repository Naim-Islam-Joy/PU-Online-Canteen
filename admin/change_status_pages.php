<?php
session_start();
include 'db.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 🔐 Admin session check
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo "❌ Unauthorized access.";
    exit;
}

if (isset($_POST['id']) && isset($_POST['orders_id'])) {
    $new_status = (int)$_POST['id'];
    $order_id = (int)$_POST['orders_id'];

    // অর্ডার খুঁজে বের করো
    $order_sql = mysqli_query($conn, "SELECT * FROM orders WHERE id = '$order_id'");
    if (mysqli_num_rows($order_sql) == 0) {
        echo "❌ Order not found";
        exit;
    }

    $order = mysqli_fetch_assoc($order_sql);
    $user_id = $order['user_id'];
    $total = $order['total'];
    $order_details = $order['order_name'];
    $current_status = $order['order_status'];

    // ইউজার তথ্য
    $user_sql = mysqli_query($conn, "SELECT balance, email FROM users WHERE id = '$user_id'");
    $user = mysqli_fetch_assoc($user_sql);
    $balance = $user['balance'];
    $user_email = $user['email'];
  // Confirm হলে: স্ট্যাটাস 1 থেকে 2 এবং ব্যালেন্স কাটা + ইমেইল পাঠানো
    if ($current_status == 1 && $new_status == 2) {
        if ($balance >= $total) {
            // ব্যালেন্স কাটা
            $new_balance = $balance - $total;
            mysqli_query($conn, "UPDATE users SET balance = '$new_balance' WHERE id = '$user_id'");
            mysqli_query($conn, "UPDATE orders SET order_status = 2 WHERE id = '$order_id'");

            // ✅ ইমেইল পাঠানো
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'naimarima03@gmail.com';      // ✅ তোমার Gmail
                $mail->Password = 'ffec lqnw saee acqj';        // ✅ Gmail App Password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('naimarima03@gmail.com', 'PU Canteen');
                $mail->addAddress($user_email);

                $mail->isHTML(true);
                $mail->Subject = 'Order Confirmed';
                $mail->Body = "
                    <h3>Dear Customer,</h3>
                    <p>Your order has been <strong>confirmed</strong> by the admin.</p>
                    <p><strong>Items:</strong> $order_details</p>
                    <p><strong>Total:</strong> ৳$total</p>
                    <p>Thank you for ordering from <strong>PU Canteen</strong>.</p>
                ";

                $mail->send();
                echo "✅ Order confirmed, balance deducted, and email sent.";
            } catch (Exception $e) {
                echo "⚠ Order confirmed & balance deducted, but email failed: {$mail->ErrorInfo}";
            }
        } else {
            echo "❌ Insufficient balance. Cannot confirm.";
        }
    } else {
        // অন্যান্য স্ট্যাটাস পরিবর্তন
        mysqli_query($conn, "UPDATE orders SET order_status = '$new_status' WHERE id = '$order_id'");
        echo "✅ Status updated.";
    }
} else {
    echo "❌ Invalid request.";
}
?>
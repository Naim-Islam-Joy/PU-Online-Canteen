<?php
include 'db.php';
session_start();
error_reporting(0);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// Step 1: ইউজারের ব্যালেন্স বের করো (চেক করার জন্য, deduct korbona ekhane)
$user_query = mysqli_query($conn, "SELECT balance FROM users WHERE id='$user_id'");
$user_data = mysqli_fetch_assoc($user_query);
$current_balance = (int)$user_data['balance'];

// Step 2: কার্ট থেকে সব পণ্যের দাম যোগ করো
$sel = "SELECT * FROM product a, cart b WHERE a.id = b.product_id AND b.user_id='$user_id'";
$ex = mysqli_query($conn, $sel);

$total = 0;
$product_name = [];
$product_id = [];

while($row = mysqli_fetch_array($ex)) {
    $product_name[] = $row['pr_title'] . '('.$row['quantity'].')';
    $product_id[] = $row['product_id'];
    
    $price = floatval($row['pr_price']);
    $qty = intval($row['quantity']);
    
    $total_price = $price * $qty;
    $total += $total_price;
}

// ঠিক এই লাইনে চেক করো:
if (bccomp($current_balance, $total, 2) >= 0) {
    // proceed to place order
    $imp_convert = implode(", ", $product_name);
    $prod_id = implode(",", $product_id);

    // অর্ডার ইনসার্ট
    $insert = "INSERT INTO orders (order_name, total, user_id, product_id, order_status) 
               VALUES ('$imp_convert', '$total', '$user_id', '$prod_id', 1)";
    $ex = mysqli_query($conn, $insert);

    if ($ex) {
    // ✅ Clear cart
        mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");


        echo "<script>alert('🕒 Order placed and waiting for admin confirmation.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('❌ Order failed. Please try again.'); window.location='cart.php';</script>";
    }

} else {
    echo "<script>alert('⚠️ Insufficient Balance! Please top up your wallet.'); window.location='cart.php';</script>";
}

?>

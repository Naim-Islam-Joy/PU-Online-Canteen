<?php
session_start();
include 'db.php';

// 🔐 Admin check
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit;
}

$id = $_GET['id'];

// Delete product image file (optional but safer)
$get = mysqli_query($conn, "SELECT img FROM product WHERE id = $id");
$row = mysqli_fetch_assoc($get);
if (file_exists($row['img'])) {
    unlink($row['img']);
}

// Delete from database
$sql = "DELETE FROM product WHERE id = $id";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Product deleted successfully'); window.location.href='add_product.php';</script>";
} else {
    echo "<script>alert('Delete failed'); window.location.href='add_product.php';</script>";
}
?>

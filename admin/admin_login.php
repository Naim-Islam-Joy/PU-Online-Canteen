<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $query = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if ($password === $admin['password']) {
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_name'] = $admin['email'];
            header("Location: index.php");
            exit;
        } else {
            echo "<script>alert('❌ Wrong password'); window.location.href='admin_login.html';</script>";
        }
    } else {
        echo "<script>alert('❌ Admin not found'); window.location.href='admin_login.html';</script>";
    }
}
?>


 


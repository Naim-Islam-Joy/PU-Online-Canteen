<?php
session_start();
include "db.php"; // MySQL connection file

 if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $email = $_POST['email'] ?? '';
   $password = $_POST['password'] ?? '';
  
}


    // প্রথমে শুধু ইমেইল দিয়ে ইউজার খুঁজবে
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // ইউজার যদি মিলে
    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // এখন hash করা পাসওয়ার্ড verify করবে
        if (password_verify($password, $user['password'])) {
           $_SESSION['user_id'] = $user['id'];
           $_SESSION['user_name'] = $user['full_name']; // fullName ধরেছি
			

            header("Location: index.php"); // বা cart.php বা dashboard.php
            exit();
        } else {
            echo "<script>alert('❌ Incorrect password.'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('❌ No user found with this email.'); window.location.href='login.html';</script>";
    }
?>

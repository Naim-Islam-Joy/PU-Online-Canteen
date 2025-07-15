<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "pu_canteen";

$conn = new mysqli($host, $user, $pass, $db);

// Connection check
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get POST data securely
$fullName   = $conn->real_escape_string($_POST['fullName']);
$phone      = $conn->real_escape_string($_POST['phone']);
$email      = $conn->real_escape_string($_POST['email']);
$customerId = $conn->real_escape_string($_POST['customerId']);
$balance    = $conn->real_escape_string($_POST['balance']);
$password   = password_hash($_POST['password'], PASSWORD_DEFAULT); // Password hashed

// Insert query
$sql = "INSERT INTO users (full_name, phone, email, customer_id, balance, password) 
        VALUES ('$fullName', '$phone', '$email', '$customerId', '$balance', '$password')";

// Insert result
if ($conn->query($sql) === TRUE) {
  echo "<script>alert('✅ Sign Up Successful! Now Login'); window.location.href='login.html';</script>";
} else {
  echo "❌ Error: " . $conn->error;
}

$conn->close();
?>


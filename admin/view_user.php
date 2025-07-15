<?php
session_start();
include 'db.php';


$id = $_GET['id'] ?? null;
if (!$id) {
    die("ইউজার আইডি পাওয়া যায়নি।");
}

$result = $conn->query("SELECT * FROM users WHERE id = $id");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>User Details</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-5">
  <h3>👁️ ইউজারের বিস্তারিত</h3>
  <table class="table table-bordered w-50">
    <tr><th>ID</th><td><?= $user['id'] ?></td></tr>
    <tr><th>Name</th><td><?= htmlspecialchars($user['full_name']) ?></td></tr>
    <tr><th>Phone</th><td><?= htmlspecialchars($user['phone']) ?></td></tr>
	<tr><th>email</th><td><?= htmlspecialchars($user['email']) ?></td></tr>
  </table>
  <a href="all_users.php" class="btn btn-secondary">↩️ Back</a>
</body>
</html>

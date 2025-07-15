<?php
session_start();
include 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("User not found।");
}

if (isset($_POST['confirm'])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: all_users.php");
    exit;
}

$result = $conn->query("SELECT * FROM users WHERE id = $id");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>Delete User</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-5">
  <h3>🗑️ Sure delete this user?</h3>
  <p>Are you sure <strong><?= htmlspecialchars($user['full_name']) ?></strong> delete?</p>
  <form method="post">
    <button type="submit" name="confirm" class="btn btn-danger">Yess, Delete</button>
    <a href="all_users.php" class="btn btn-secondary">No, Cancel</a>
  </form>
</body>
</html>

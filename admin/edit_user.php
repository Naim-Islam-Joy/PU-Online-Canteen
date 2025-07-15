<?php
session_start();
include 'db.php';



$id = $_GET['id'] ?? null;
if (!$id) {
    die("User not found");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['full_name'];
	$phone = $_POST['phone'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE users SET full_name=?, phone=?, email=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $phone, $email, $id);
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
  <title>Edit User</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-5">
  <h3>✏️ User Update</h3>
  <form method="post">
    <div class="form-group">
      <label>Name:</label>
      <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
    </div>
	<div class="form-group">
      <label>Phone:</label>
      <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
    </div>
    <div class="form-group">
      <label>Email:</label>
      <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>
    <button type="submit" class="btn btn-success">✅ Save</button>
    <a href="all_users.php" class="btn btn-secondary">↩️ Back</a>
  </form>
</body>
</html>
_
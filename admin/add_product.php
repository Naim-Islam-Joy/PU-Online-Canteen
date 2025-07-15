<?php
session_start();
include 'db.php';

// 🔐 নিরাপত্তা চেক
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit;
}

if (isset($_POST['submit'])) {
    $pr_title = $_POST['pr_title'];
    $pr_desc = $_POST['pr_desc'];
    $pr_price = $_POST['pr_price'];
    $img_name = $_FILES['img']['name'];
    $tmp_name = $_FILES['img']['tmp_name'];

    $upload_dir = "uploads/";
    $target_file = $upload_dir . basename($img_name);

    if (move_uploaded_file($tmp_name, $target_file)) {
        $ins = "INSERT INTO product (pr_title, pr_desc, pr_price, img) 
                VALUES('$pr_title','$pr_desc','$pr_price','$target_file')";
        $ex = mysqli_query($conn, $ins);

        if ($ex) {
            echo "<script>alert('Product added successfully!')</script>";
        } else {
            echo "<script>alert('Failed to insert into database')</script>";
        }
    } else {
        echo "<script>alert('Failed to upload image')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>প্রোডাক্ট যোগ করুন - PU Canteen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f2f2f2;
            font-family: 'Noto Sans Bengali', sans-serif;
        }

        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 30px;
            position: fixed;
            width: 220px;
        }

        .sidebar h4 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .main-content {
            margin-left: 230px;
            padding: 30px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>📊 Admin Panel</h4>
    <a href="index.php">🏠 Dashboard</a>
	<a href="manage_orders.php">🧾 Manage Orders</a>
    <a href="admin_orders.php">📦 All Orders</a>
    <a href="add_product.php">➕ Add Product</a>
    <a href="all_users.php">👤 All Users</a>
	<a href="admin_balance_requests.php">💳 Balance Requests</a>
    <a href="report.php">🕒 Pending Reports</a>
    <a href="confirm_report.php">✅ Confirm Reports</a>
    <a href="admin_logout.php">🚪 Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <h2>➕ Add Product</h2>
    <form method="POST" enctype="multipart/form-data" class="mb-5">
        <div class="mb-3">
            <label class="form-label">Product Title</label>
            <input type="text" name="pr_title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Product Description</label>
            <input type="text" name="pr_desc" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Product Price</label>
            <input type="number" name="pr_price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="img" class="form-control" required>
        </div>
        <button name="submit" type="submit" class="btn btn-primary">Add Product</button>
    </form>

    <h4>📦 All Products</h4>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Price</th>
            <th>Image</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sel = "SELECT * FROM product";
        $ex = mysqli_query($conn, $sel);
        while ($row = mysqli_fetch_array($ex)) {
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['pr_title']) ?></td>
                <td><?= htmlspecialchars($row['pr_desc']) ?></td>
                <td>৳<?= htmlspecialchars($row['pr_price']) ?></td>
                <td><img src="<?= htmlspecialchars($row['img']) ?>" width="100" height="100" alt="product"></td>
            </tr>
			<td>
            <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
           <a href="delete_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?');" class="btn btn-danger btn-sm">Delete</a>
           </td>
        <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>
</html>

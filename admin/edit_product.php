<?php
session_start();
include 'db.php';

// 🔐 Admin authentication check
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// 🛒 Load product info
$id = $_GET['id'];
$sql = "SELECT * FROM product WHERE id = $id";
$res = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($res);

$error = "";

// 🔄 Update on form submit
if (isset($_POST['update'])) {
    $pr_title = $_POST['pr_title'];
    $pr_desc = $_POST['pr_desc'];
    $pr_price = $_POST['pr_price'];
    $old_img = $_POST['old_img'];

    $final_img = $old_img;

    if (!empty($_FILES['img']['name'])) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        $upload_dir = "uploads/";
        $original_name = $_FILES['img']['name'];
        $tmp_name = $_FILES['img']['tmp_name'];

        // extract extension
        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_ext)) {
            // new unique filename
            $new_name = time() . "_" . uniqid() . "." . $ext;
            $target_file = $upload_dir . $new_name;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $final_img = $target_file;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Only JPG, JPEG, PNG, and WEBP files are allowed.";
        }
    }

    if ($error === "") {
        $update_sql = "UPDATE product 
                       SET pr_title='$pr_title', pr_desc='$pr_desc', pr_price='$pr_price', img='$final_img' 
                       WHERE id=$id";
        if (mysqli_query($conn, $update_sql)) {
            echo "<script>alert('Product updated successfully'); window.location.href='add_product.php';</script>";
            exit;
        } else {
            $error = "Database update failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>✏️ প্রোডাক্ট এডিট করুন</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5 bg-light">
    <div class="container">
        <h2 class="mb-4">✏️ Edit Product</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Product Title</label>
                <input type="text" name="pr_title" value="<?= htmlspecialchars($product['pr_title']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Product Description</label>
                <input type="text" name="pr_desc" value="<?= htmlspecialchars($product['pr_desc']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Product Price</label>
                <input type="number" name="pr_price" value="<?= htmlspecialchars($product['pr_price']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Upload New Image (optional)</label>
                <input type="file" name="img" class="form-control">
                <input type="hidden" name="old_img" value="<?= htmlspecialchars($product['img']) ?>">
                <?php if (!empty($product['img'])): ?>
                    <div class="mt-2">
                        <img src="<?= htmlspecialchars($product['img']) ?>" width="120" height="120" alt="Current Image">
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" name="update" class="btn btn-success">Update</button>
            <a href="add_product.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>

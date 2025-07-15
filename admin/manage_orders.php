<?php
session_start();
include 'db.php';

// 🔐 নিরাপত্তা চেক
  if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
  header("Location: admin_login.html");
    exit;
  }
   

// অর্ডার এবং ইউজার তথ্য
$sql = "SELECT orders.*, users.full_name, users.email 
        FROM orders 
        JOIN users ON orders.user_id = users.id 
        ORDER BY orders.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>অ্যাডমিন প্যানেল - PU Canteen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

    .card {
      border-radius: 10px;
    }

    .order-header {
      background-color: #343a40;
      color: white;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      padding: 15px;
    }

    .item-list li {
      font-size: 15px;
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
    <h3 class="mb-4">📦 Order List</h3>

    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card mb-4 shadow-sm">
          <div class="order-header">
            <strong>Order ID:</strong> <?= $row['id'] ?> |
            <strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($row['created_at'])) ?><br>
            <strong>Customer:</strong> <?= htmlspecialchars($row['full_name']) ?> (<?= $row['email'] ?>)<br>
            <strong>Total:</strong> TK<?= number_format($row['total'], 2) ?>
          </div>
          <ul class="list-group list-group-flush item-list">
            <?php
              $items = $row['order_name'];
              if ($row['order_name']) {
              
            ?>
              <li class="list-group-item">
                🥘 <?= $row['order_name'] ?> - Tk<?= number_format($row['total'], 2) ?>
              </li>
            <?php
              
              } else {
                echo "<li class='list-group-item text-danger'>❌ পণ্যের তথ্য পাওয়া যায়নি</li>";
              }
            ?>
            <li>
              <?php
                $inStatus = array(1=>"Pending",2=>"Confirm");

                if($row['order_status'] == 2)
                {
                  ?>
                    <select onchange="OnStatusChanges(this.value,<?php echo $row['id']; ?>)" name="order_status" class="form-controll" id="order_status"  >
                        
                        <option value="<?php echo $row['order_status'] ?>"  ><?php echo $inStatus[$row['order_status']];?></option>
                    </select>
         
                  <?php
                }
                 else 
                {
                    ?>
                        <select onchange="OnStatusChanges(this.value,<?php echo $row['id']; ?>)" name="order_status" class="form-controll" id="order_status"  >
                
                          <?php
                            foreach($inStatus as $key=>$val)
                            {
                              ?>
                                  <option value="<?php echo $key; ?>"  ><?php echo $val;?></option>
                              <?php
                            }
                          ?>
                      </select>
         
                    <?php 
                }
               ?>
              
            </li>
          </ul>
          
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="alert alert-info text-center">⚠️ Any Order Not Found</div>
    <?php endif; ?>
  </div>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    
    function OnStatusChanges(id,orders_id)
    {
        $.ajax({
          url:"change_status_pages.php",
          method:"POST",
          data:{id:id,orders_id:orders_id},
          success:function(resp)
          {
              console.log(resp)
          }
        })
    }
  </script>
</body>
</html>

<?php
include 'db.php';
session_start();
$user_id = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

if(isset($_POST['cart'])){
   
    $prod_id = $_POST['prod_id'];
    $user_id = $_POST['user_id'];
    $check = "SELECT * FROM cart WHERE product_id='$prod_id' AND user_id='$user_id' ";
    $resutl = mysqli_query($conn,$check);
    $count = mysqli_num_rows($resutl)>0;
    if($count){
     echo "<script>alert('product already in cart')</script>";
    }else{
  
    
    $insert = "INSERT INTO cart(user_id,product_id) 
    VALUES('$user_id','$prod_id')";
    $query = mysqli_query($conn,$insert);
    if($query){
        echo "<script>alert('product add to cart')</script>";
    }
    else{
        echo "<script>alert('error')</script>";
    }
}

}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Canteen Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>

<body>
       
    <?php 
        $sql = "SELECT * FROM cart";
        $ex = mysqli_query($conn,$sql);
        $num = mysqli_num_rows($ex);
    
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-info shadow-sm sticky-top">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
          <img src="img/logo.png" alt="PU Logo" style="height: 50px; margin-right: 10px;">
          <strong>PU Canteen</strong>
        </a>
        <div class="ml-auto d-flex align-items-center">
         
          <a href="login.html" class="btn btn-outline-light btn-sm mr-2">👤🔑 Logout</a>
          <!--a href="admin/admin_login.html" class="btn btn-outline-warning btn-sm mr-2">🛠️ Admin Login</a-->
		  <a href="my_profile.php" class="btn btn-outline-warning btn-sm mr-2">👤 My Profile</a>
          <a href="cart.php">
            <span class="btn btn-light btn-sm">
              🛒 Cart: <span id="total"> <?php echo $num;?></span>
            </span>   
          </a>
          
        </div>
      </div>
    </nav>

   <br><br>
    <div class="container">
        <div class="row">
            <?php
      $select = mysqli_query($conn,"SELECT * FROM product");
      while($row= mysqli_fetch_array($select)){?>
            <div class="col-lg-3">
               <form method="post" action="">
               <div class="card"  style="width: 100%; height:400px">
                    <img style="width: 100%; height: 200px; object-fit: cover;" src="admin/<?php echo $row['img'] ?>" class="card-img-top"
                        alt="...">
                        <input type="hidden" name="img" value="<?php echo $row['img'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['pr_title'] ?></h5>
                        <input type="hidden" name="prod_id" value="<?php echo $row['id'] ?>">
                        <p class="card-text"><?php echo $row['pr_desc'] ?></p>
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <h5>price:<?php echo $row['pr_price'] ?> bdt</h5>
                        <input type="hidden" name="pr_price" value="<?php echo $row['pr_price'] ?>">
                       <button class="btn btn-primary " name="cart">add to cart</button>
                    </div>
                </div>
               </form>
            </div>
            <?php  }


    ?>




        </div>
    </div>

<footer class="bg-dark text-white mt-5 py-4">
      <div class="container text-center">
        <h5>📞 Contact Us</h5>
        <p>Email: <a href="mailto:support@pucanteen.com" class="text-light">support@pucanteen.com</a></p>
        <p>Phone: 123-456-7890</p>
        <div class="mt-3">
          <a href="about.html" class="text-light mr-3">About Us</a>
          <a href="#" class="text-light">Conditions</a>
        </div>
        <p class="mt-3 mb-0">© 2025 PU Canteen. Developed with Abdullah Al Naim</p>
      </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>
</body>

</html>
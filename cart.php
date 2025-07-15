<?php
 include 'db.php';
 session_start();
$user_id =  $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

  
 if(isset($_POST['update_product'])){
     $qty = $_POST['qty'];
     $product_id = $_POST['product_id'];
     $update = "UPDATE cart SET quantity='$qty' WHERE product_id='$product_id' AND user_id ='$user_id'";
     $ex = mysqli_query($conn,$update);
 }

 if(isset($_GET['dlt_id'])){
    $id = $_GET['dlt_id'];
    $dlt = "DELETE FROM cart WHERE id='$id' AND user_id='$user_id' ";
    $e = mysqli_query($conn,$dlt);
 }
 if(isset($_GET['alldelete'])){
    mysqli_query($conn,"DELETE FROM cart WHERE user_id='$user_id'");
 }
 
 ?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>total cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>
     <div class="container ">
        <div class="row">
            <h1 class='bg-success text-white p-2 text-center'>Check Your Items</h1>
          <div class="col-lg-12 mt-3">
             <table class='table'>
                 <th>id</th>
                 <th> name</th>
                 <th>img</th>
                 <th>price</th>
                <th>Qty</th>
                <th>Grand Total</th>
                <th>action</th>

                <tbody>
                    <?php 
                    $id=1;
                    $grand_total="";
                    $total = 0 ;
                      $sel = "SELECT *
                      FROM product a,cart   b
                      WHERE a.id = b.product_id AND b.user_id='$user_id' ";
                      $ex = mysqli_query($conn,$sel);
                      while($row=mysqli_fetch_array($ex)){?>
                          <tr>
                            <td><?php echo $id++ ?></td>
                            <td><?php echo $row['pr_title'] ?></td>
                            <td><img height='100' width='100' src="Admin/<?php echo $row['img'] ?>" alt=""></td>
                            <td><?php echo $row['pr_price'] ?></td>
                            <td>
                               <form method="post" action="cart.php">
                                  <input type="number" name="qty" max="5" min="1" value="<?php echo $row['quantity'] ?>">
                                  <input type="hidden" name="product_id" value="<?php echo $row['product_id'] ?>">
                                  <button name='update_product' class='btn btn-primary'>update</button>
                               </form>
                            </td>
                            <td>
                                <?php 
                                   $grand_total = (int)$row['quantity'] * (int)$row['pr_price'];
                                   echo $grand_total;
                                 ?>
                            </td>

                            <td>
                               <a href="cart.php?dlt_id=<?php echo $row['id'] ?>"> <button class='btn btn-danger'>remove</button></a>
                            </td>
                        </tr>
                          
                         <?php  
                             $total += $grand_total;
                         ?>
                       
                 <?php     }

                    ?>

                </tbody>
             </table>
             <h3>Total Taka: <?php  echo $total ?> <a href="order.php"><button class="btn btn-success">Order Now</button> </a></h3>
             <a href="cart.php?alldelete="><button class="btn btn-warning">Remove All Items</button></a>
             <br>
            <a href="index.php">continue Items</a>
            </div>
        
        </div>
     </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
  </body>
</html>
<?php
    include 'ServerSide/functions.php';
    include 'ServerSide/db_connect.php';
    sec_session_start();
    $category = "d0018e_categories";
    $orders = "d0018e_orders";
    $products = "d0018e_products";
    $carts = "d0018e_carts";

    $manufacturer = "d0018e_manufacturers";

    $mysqli = new mysqli("localhost", "skola", "skola", "skola");
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    
    $mysqli->set_charset("utf8");
?>

<!DOCTYPE html>
<html>
<head><meta charset="iso-8859-1"></head>
<title>Basic frontpage design</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="dropdown.css">
<body>

<div class="main">

	<div class="banner">
	</div>
	
    <div class="navigationbar">
    
        <div class="navigationbar_dropdowns">
        
            <div class="dropdown">
              <button class="firstdropbtn"><a class="firstdropbtn" href="index.php">Home</a></button>
            </div>
            
            <div class="dropdown">
              <button class="dropbtn">Categories</button>
              <div class="dropdown-content">
              <?php
                $stmt = $mysqli->prepare("SELECT * FROM $category");
                $stmt->execute();
                $result = $stmt->get_result();
                
                while ($row = $result->fetch_assoc()) {
                    echo '<a href="browse.php?cat='.$row['id'].'">'.$row['name'].'</a>';
                }
                ?>
              </div>
            </div>
            
            <div class="dropdown">
              <button class="dropbtn"><a class="dropbtn" href="about.html">About ous</a></button>
            </div>
            
            <div class="dropdown">
              <button class="dropbtn"><a class="dropbtn" href="contact.html">Contact</a></button>
            </div>
            
            
            <div class="dropdown">
              <button class="dropbtn"><a class="dropbtn" href="login.php">Login</a></button>
              <div class="dropdown-content">
                <a href="register.php">Register</a>
              </div>
            </div>
                    <div class="shopping_cart">
              <?php
              if(login_check() == true){
                $user_id = $_SESSION['user_id'];
              $cart_id = $_SESSION['cart_id'];
              $quantity1=0;
              if($select_stmt = $mysqli->prepare("SELECT quantity from d0018e_cart_details WHERE cart = ?")){
                $select_stmt->bind_param('s', $cart_id);
                $select_stmt->execute();
                $select_stmt->store_result();
                $select_stmt->bind_result($quantity);
                while($select_stmt->fetch()){
                $quantity1=$quantity1+$quantity;
                }
                echo $quantity1 . ' items in the shopping basket';
              }
            }else{
              echo 'Log in to use shopping cart';
            }
            ?>
            </div>
        </div>        
    </div>
    
	<div class="mainbody">
    
        <div class="mainborders">
        
           <p class="mainbody_text">
	   
       <?php
            $get = $_GET['cat'];
            
            $stmt = $mysqli->prepare("SELECT $products.id, $manufacturer.name AS manufacturername, $products.name, 
            $products.shortdesc, $products.cost, $products.stock, $products.manufacturer 
            FROM $products 
            LEFT JOIN $manufacturer ON $products.manufacturer = $manufacturer.id WHERE category = ?");
            $stmt->bind_param("i", $get);
            $stmt->execute();
            
            if(!$stmt) {
                echo 'Something went wrong: ' . $mysqli->error;
            } else {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo '      <form action="addtocart.php">
                                  <div class="product_box">
                                      <div class="product_img"><img src="img/mouse1.jpg" class="thumb"></div>
                                      <div class="product_text">
                                          <div class="product_header"><a class="product_link" href="product.php?id=' . $row['id'] . '">' . $row['manufacturername'] . ' - ' . $row['name'] . '</a></div>
                                          <div class="product_desc">' . $row['shortdesc'] . '</div>
                                          <div class="product_available">' . $row['stock'] . ' in stock</div>
                                          <div class="product_id">Product number. ' . $row['id'] . '</div>
                                      </div>
                                      <div class="product_price">' . $row['cost'] . ':-&nbsp;<button type="submit" name="btnValue" id="btnValue" class="product_buy" value='. $row['id'] .' >Buy</div>
                                  </div>
                              </form>';
                    }
            }
            $stmt->close();
       ?>
	   
	   </p>
        
        </div>
    
	</div>
	
	<div class="footer">
		<p class="copyright_text"> Christoffer Rova & Simon Herbertsson &copy; 2017</p>
	</div>

</div>

</body>
</html>
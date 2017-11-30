<?php
include 'ServerSide/functions.php';
include 'ServerSide/db_connect.php';
sec_session_start();
?>
<!DOCTYPE html>
<html>
<title>Basic frontpage design</title>
<script src="ServerSide/jquery-3.2.1.min.js"></script>
<script src="ServerSide/jquery.js"></script>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="dropdown.css">
<body>

<div class="main">

	<div class="banner">
	</div>
	
    <div class="navigationbar">
    
        <div class="navigationbar_dropdowns">
        
            <div class="dropdown">
              <button class="firstdropbtn"><a class="firstdropbtn" href="index.html">Home</a></button>
            </div>
            
            <div class="dropdown">
              <button class="dropbtn">Categories</button>
              <div class="dropdown-content">
                <a href="mouse.html">Mouse</a>
              </div>
            </div>
            
            <div class="dropdown">
              <button class="dropbtn"><a class="dropbtn" href="about.html">About ous</a></button>
            </div>
            
            <div class="dropdown">
              <button class="dropbtn"><a class="dropbtn" href="contact.html">Contact</a></button>
            </div>
            
            
            <div class="dropdown">
              <button class="dropbtn"><a class="dropbtn" href="login.html">Login</a></button>
              <div class="dropdown-content">
                <a href="register.html">Register</a>
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
	   
	   <div class="product_box">
	      <div class="product_img"><img src="img/mouse1.jpg" class="thumb"></div>
	      <div class="product_text">
	         <div class="product_header">Logitech MX Anywhere 2 Trådlös Mus</div>
	         <div class="product_desc">unifying, bluetooth, uppladdningsbar, 5 knappar, 1600 dpi, laser mus</div>
		 <div class="product_available">999+ I lager</div>
	         <div class="product_id">Art. nr. 000001</div>
	      </div>
	      <div class="product_price">990:-&nbsp;
          <form action="serverSide/addtocart.php" method="post" name="addtocart">
            <button type="submit" name="btnValue" id="btnValue" class="button" value="1">Buy</button></div>

	   </div>
	   
	   </p>
        
        </div>
    
	</div>
	
	<div class="footer">
		<p class="copyright_text"> Christoffer Rova & Simon Herbertsson &copy; 2017</p>
	</div>

</div>

</body>

</html>

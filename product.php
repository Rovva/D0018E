<?php
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
              <button class="dropbtn"><a class="dropbtn" href="login.html">Login</a></button>
              <div class="dropdown-content">
                <a href="register.html">Register</a>
              </div>
            </div>
        
        </div>        
    </div>
    
	<div class="mainbody">
    
        <div class="mainborders">
        
           <p class="mainbody_text">
           
           
           <?php
           
           $get = $_GET['id'];
           
           
           $stmt = $mysqli->prepare("SELECT * FROM d0018e_images");
           $stmt->execute();
            
           $result = $stmt->get_result();
           while($row = $result->fetch_assoc()) {
               $images[$row['filename']] = $row['id'];
           }
           
           $stmt = $mysqli->prepare("SELECT $products.id, $manufacturer.name AS manufacturername, $products.name, 
           $products.longdesc, $products.cost, $products.stock, $products.manufacturer, $products.image 
           FROM $products 
           LEFT JOIN $manufacturer ON $products.manufacturer = $manufacturer.id WHERE $products.id = ?");
           $stmt->bind_param("i", $get);
           $stmt->execute();
            
           $result = $stmt->get_result();
           while ($row = $result->fetch_assoc()) {
                $filename = "";
                foreach($images as $img_name => $img_id) {
                    if($img_id == $row['image']) {
                        $filename = '<img width="210px" src="' . $img_name . '">';
                    }
                }
                echo '
                    <form action="addtocart.php">
                        <div class="productdetail_box">
                            <div class="productdetail_img">' . $filename . '</div>
                            <div class="productdetail_header">' . $row['manufacturername'] . ' - ' . $row['name'] . '</div>
                            <div class="productdetail_desc">' . $row['longdesc'] . '</div>
                            <div class="productdetail_bottom"><div class="productdetail_stock">' . $row['stock'] . ' in stock</div><div class="productdetail_price">' . $row['cost'] . ':- <input type="button" value="Buy"></div></div>
                        </div>
                    </form>';
           }
           
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
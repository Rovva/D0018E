<?php
    $category = "d0018e_categories";
    $orders = "d0018e_orders";
    $products = "d0018e_products";
    $carts = "d0018e_carts";
    
    $manufacturer = "d0018e_manufacturers";
    
    $dbhost = "localhost";
    $dbname = "skola";
    $dbusr = "skola";
    $dbpass = "skola";
    global $conn;
    $conn = mysql_connect($dbhost, $dbusr, $dbpass);
    mysql_select_db($dbname);
    mysql_set_charset("utf8", $conn);
    if (!$conn) { 
        die('Could not establish a connection: ' . mysql_error());
    }
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
                $sql = "SELECT * FROM $category";
                            $out = mysql_query($sql, $conn);
                            if(!$out) { die('Could not fetch any data: ' . mysql_error()); }

                            while ($row = mysql_fetch_array($out)) {
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
        
           <p class="mainbody_text">Welcome to our webstore!
	   </p>
        
        </div>
    
	</div>
	
	<div class="footer">
		<p class="copyright_text"> Christoffer Rova & Simon Herbertsson &copy; 2017</p>
	</div>

</div>

</body>
</html>
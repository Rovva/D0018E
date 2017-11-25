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
<title>Adminpage</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="dropdown.css">
<body>

<div class="main">

	<div class="banner">
	</div>
	
    <div class="navigationbar">
    
        <div class="navigationbar_dropdowns">
        
        <?php
        include("admin_menu.html");
        ?>
        
        </div>        
    </div>
    
	<div class="mainbody">
    
        <div class="mainborders">
        
           <p class="mainbody_text">
           
            <table style="width: 100%;">
                <tr>
                    <th style="text-align: left; width: 50px;">Id</th>
                    <th style="text-align: left; width: 150px;">Name</th>
                    <th style="text-align: left; width: 50px;">URL</th>
                    <th style="width: 20px;"></th>
                    <th style="text-align: left; width: 150px;"></th>
                    <th style="text-align: left;"></th>
                </tr>
                <?php
                    $sql = "SELECT * FROM $manufacturer ORDER BY id";
                    $query = mysql_query($sql);
                    while($row = mysql_fetch_array($query)) {
                        echo '                <tr>
                    <form action="admin_manufacturer.php">
                        <input type="hidden" name="id" value="' . $row['id'] . '">
                        <th style="text-align: left;">' . $row['id'] . '</th>
                        <th style="text-align: left;">' . $row['name'] . '</th>
                        <th style="text-align: left;">' . $row['url'] . '</th>
                        <th></th>
                        <th style="text-align: left;"><input type="button" name="edit" value="Edit manufacturer"></th>
                        <th style="text-align: left;"><input type="button" name="remove" value="Remove manufacturer"></th>
                    </form>
                </tr>';
                    }
                ?>
                <tr>
                    <form action="admin_manufacturer.php">
                        <th style="text-align: left;"><input type="button" name="add" value="Add manufacturer"></th>
                        <th style="text-align: left;"></th>
                        <th style="text-align: left;"></th>
                        <th></th>
                        <th style="text-align: left;"></th>
                        <th style="text-align: left;"></th>
                    </form>
                </tr>
            </table>
            
            
	   </p>
        
        </div>
    
	</div>
	
	<div class="footer">
		<p class="copyright_text"> Christoffer Rova & Simon Herbertsson &copy; 2017</p>
	</div>

</div>

</body>
</html>
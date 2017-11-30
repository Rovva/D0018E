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
        
    function list_items($table, $table2) {
        
        echo '
            <table style="width: 100%;">
                <tr>
                    <th style="text-align: left; width: 50px;">Id</th>
                    <th style="text-align: left; width: 50px;">Manufacturer</th>
                    <th style="text-align: left; width: 150px;">Name</th>
                    <th style="text-align: left; width: 50px;">Cost</th>
                    <th style="text-align: left; width: 50px;">Stock</th>
                    <th style="width: 20px;"></th>
                    <th style="text-align: left; width: 150px;"></th>
                    <th style="text-align: left; width: 150px;"></th>
                    <th style="text-align: left;"></th>
                </tr>';
                        
        $sql = "SELECT $table.id, $table2.name AS manufacturername, $table.name, 
        $table.cost, $table.stock, $table.manufacturer 
        FROM $table 
        LEFT JOIN $table2 ON $table.manufacturer = $table2.id";
                    
        $query = mysql_query($sql);
        if(!$query) {
            echo 'Something went wrong: ' . mysql_error();
        }
        while($row = mysql_fetch_array($query)) {
            echo '                <tr>
                    <form action="admin_products.php" method="post">
                        <input type="hidden" name="id" value="' . $row['id'] . '">
                        <th style="text-align: left;">' . $row['id'] . '</th>
                        <th style="text-align: left;">' . $row['manufacturername'] . '</th>
                        <th style="text-align: left;">' . $row['name'] . '</th>
                        <th style="text-align: left;">' . $row['cost'] . ':-</th>
                        <th style="text-align: left;">' . $row['stock'] . '</th>
                        <th></th>
                        <th style="text-align: left;"><input type="submit" name="view" value="View product"></th>
                        <th style="text-align: left;"><input type="submit" name="edit" value="Edit product"></th>
                        <th style="text-align: left;"><input type="submit" name="remove" value="Remove product"></th>
                    </form>
                </tr>';
        }
        
        echo '
                <tr style="height: 20px;">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <form action="admin_products.php" method="post">
                    <th><input type="submit" name="add" value="Add product"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="width: 20px;"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    </form>
                </tr>
            </table>';
    }
    
    
    function view_item($table, $table2) {
        $id = $_POST['id'];
        $sql = "SELECT *, $table.name AS prod_name, $table2.name AS man_name FROM $table 
            LEFT JOIN $table2 ON $table.manufacturer = $table2.id WHERE $table.id = $id";
        $query = mysql_query($sql);
        
        if(!$query) {
            echo 'Something went wrong: ' . mysql_error();
        }
        
        echo '<table>
                <tr>
                    <th>ID</th>
                    <th>Manufacturer</th>
                    <th>Name</th>
                    <th>Short desc.</th>
                    <th>Long desc.</th>
                    <th>Cost</th>
                    <th>Stock</th>
                </tr>
                ';
        
        while($row = mysql_fetch_array($query)) {
            echo '<tr>
                    <th>' . $row['id'] . '</th>
                    <th>' . $row['man_name'] . '</th>
                    <th>' . $row['prod_name'] . '</th>
                    <th>' . $row['shortDesc'] . '</th>
                    <th>' . $row['longDesc'] . '</th>
                    <th>' . $row['cost'] . '</th>
                    <th>' . $row['stock'] . '</th>
                </tr>';
        }
        
        echo '</table>';
        
        
    }
    
    function add_item($table) {
        $sql = "SELECT id, name FROM $table";
        $sql2 = "SELECT id, name FROM d0018e_categories";
        $query2 = mysql_query($sql2);
        $query = mysql_query($sql);
        if(!$query) {
            echo 'Something went wrong: ' . mysql_error();
        }
        echo '                <form action="admin_products.php" method="post">
                    Name:<br>
                    <input type="text" name="name"><br>
                    Manufacturer:<br>
                    <select name="manufacturer">
                    ';
                    
                    while($row = mysql_fetch_array($query)) {
                        echo '                      <option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                    }
                    
                    echo '
                    </select><br>
                    <select name="category">';
                    
                    while($row = mysql_fetch_array($query2)) {
                        echo '                      <option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                    }
                    echo '
                    </select>
                    Short description:<br>
                    <textarea name="shortdesc" rows="4" cols="50"></textarea><br>
                    Long description:<br>
                    <textarea name="longdesc" rows="4" cols="50"></textarea><br>
                    Cost:<br>
                    <input type="text" name="cost"><br>
                    Stock:<br>
                    <input type="text" name="stock"><br>
                    <input type="submit" name="add_finish" value="Add item">
                    </form>';
    }
    
    function add_item_finish($table) {
        $name = $_POST['name'];
        $manufacturer = $_POST['manufacturer'];
        $category_name = $_POST['category'];
        $shortdesc = $_POST['shortdesc'];
        $longdesc = $_POST['longdesc'];
        $cost = $_POST['cost'];
        $stock = $_POST['stock'];
        $sql = "INSERT INTO $table (name, manufacturer, category, shortDesc, longDesc, cost, stock) VALUES ('$name', '$manufacturer',
           '$category_name' ,'$shortdesc', '$longdesc', '$cost', '$stock')";
        $query = mysql_query($sql);
        if($query) {
            echo 'Item has been added';
        } else {
            echo 'Something went wrong: ' . mysql_error();
        }
    }
    
    function edit_item($table, $table2) {
        $id = $_POST['id'];
        $sql = "SELECT id, name FROM $table2";
        $sql2 = "SELECT id, name FROM d0018e_categories";
        $query2 = mysql_query($sql2);
        $query = mysql_query($sql);
        
        if(!$query) {
            echo 'Something went wrong: ' . mysql_error();
        }
        
        while($row = mysql_fetch_array($query)) {
            $manufacturer[$row['name']] = $row['id'];
        }
        
        while($row = mysql_fetch_array($query2)) {
            $categories[$row['name']] = $row['id'];
        }
        
        $sql = "SELECT * FROM $table WHERE id = $id";
        $query = mysql_query($sql);
        
        if(!$query) {
            echo 'Something went wrong: ' . mysql_error();
        }
        
        $row = mysql_fetch_array($query);
        echo '                <form action="admin_products.php" method="post">
                    <input type="hidden" name="id" value="' . $row['id'] . '">
                    Name: <input type="text" name="name" value="' . $row['name'] . '"><br>
                    Manufacturer: <select name="manufacturer">
                    ';
                    
                    foreach($manufacturer as $man_name => $man_id) {
                        if($man_id == $row['manufacturer']) {
                            echo '                      <option selected value="' . $man_id . '">' . $man_name . '</option>
                            ';
                        } else {
                            echo '                      <option value="' . $man_id . '">' . $man_name . '</option>
                            ';
                        }
                    }
                    
                    echo '
                    </select><br>
                    <select name="category">';
                    
                    foreach($categories as $cat_name => $cat_id) {
                        if($cat_id == $row['category']) {
                            echo '                      <option selected value="' . $cat_id . '">' . $cat_name . '</option>';
                        } else {
                            echo '                      <option value="' . $cat_id . '">' . $cat_name . '</option>';
                        }
                    }
                    echo '
                    </select>
                    Short description: <textarea name="shortdesc" rows="4" cols="50">' . $row['shortDesc'] . '</textarea><br>
                    Long description: <textarea name="longdesc" rows="4" cols="50">' . $row['longDesc'] . '</textarea><br>
                    Cost: <input type="text" name="cost" value="' . $row['cost'] . '"><br>
                    Stock: <input type="text" name="stock" value="' . $row['stock'] . '"><br>
                    <input type="submit" name="edit_finish" value="Save data">
                    </form>';
    }
    
    function edit_item_finish($table) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $manufacturer = $_POST['manufacturer'];
        $shortdesc = $_POST['shortdesc'];
        $longdesc = $_POST['longdesc'];
        $cost = $_POST['cost'];
        $stock = $_POST['stock'];
        $sql = "UPDATE $table SET name = '$name', manufacturer = '$manufacturer', shortDesc = '$shortdesc', 
            longDesc = '$longdesc', cost = '$cost', stock = '$stock' WHERE id = $id";
        $query = mysql_query($sql);
        if($query) {
            echo 'Successfully edited item.';
        } else {
            echo 'Something went wrong: ' . mysql_error();
        }
    }
    
    function remove_item() {
        echo'                 Are you sure you want to delete selected item?
                    <form action="admin_products.php" method="post">
                    <input hidden="id" name="id" value="' . $_POST['id'] . '">
                    <input type="submit" name="remove_finish" value="Remove">
                    </form>';
    }
    
    function remove_item_finish($table) {
        $id = $_POST['id'];
        $sql = "DELETE FROM $table WHERE id = $id";
        $query = mysql_query($sql);
        if($query) {
            echo 'Sucessfully deleted item.';
        } else {
            echo 'Something went wrong: ' . mysql_error();
        }
    }
    
    function select_function($table, $table2) {
        if($_POST['view']) {
            view_item($table, $table2);
        } else if($_POST['add']) {
            add_item($table2);
        } else if($_POST['edit']) {
            edit_item($table, $table2);
        } else if($_POST['remove']) {
            remove_item();
        } else if($_POST['add_finish']) {
            add_item_finish($table);
        } else if($_POST['edit_finish']) {
            edit_item_finish($table);
        } else if($_POST['remove_finish']){
            remove_item_finish($table);
        } else {
            list_items($table, $table2);
        }
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
           
                <?php
                    select_function($products, $manufacturer);
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
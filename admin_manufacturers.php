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
    
    function list_items($table) {
        echo '
            <table style="width: 100%;">
                <tr>
                    <th style="text-align: left; width: 50px;">Id</th>
                    <th style="text-align: left; width: 150px;">Name</th>
                    <th style="text-align: left; width: 50px;">URL</th>
                    <th style="width: 20px;"></th>
                    <th style="text-align: left; width: 150px;"></th>
                    <th style="text-align: left;"></th>
                </tr>';
        
        $sql = "SELECT * FROM $table";
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)) {
            echo '                <tr>
                    <form action="admin_manufacturers.php" method="post">
                        <input type="hidden" name="id" value="' . $row['id'] . '">
                        <th style="text-align: left;">' . $row['id'] . '</th>
                        <th style="text-align: left;">' . $row['name'] . '</th>
                        <th style="text-align: left;">' . $row['url'] . '</th>
                        <th></th>
                        <th style="text-align: left;"><input type="submit" name="edit" value="Edit manufacturer"></th>
                        <th style="text-align: left;"><input type="submit" name="remove" value="Remove manufacturer"></th>
                    </form>
                </tr>';
        }
        
        echo '
                <tr>
                    <form action="admin_manufacturers.php" method="post">
                        <th style="text-align: left;"><input type="submit" name="add" value="Add manufacturer"></th>
                        <th style="text-align: left;"></th>
                        <th style="text-align: left;"></th>
                        <th></th>
                        <th style="text-align: left;"></th>
                        <th style="text-align: left;"></th>
                    </form>
                </tr>
            </table>';
    }
    
    function add_item() {
        echo '                <form action="admin_manufacturers.php" method="post">
                    Name: <input type="text" name="name"><br>
                    URL: <input type="text" name="url"><br>
                    <input type="submit" name="add_finish" value="Add item">
                    </form>';
    }
    
    function add_item_finish($table) {
        $name = $_POST['name'];
        $url = $_POST['url'];
        $sql = "INSERT INTO $table (name, url) VALUES ('$name', '$url')";
        $query = mysql_query($sql);
        if($query) {
            echo 'Item has been added';
        } else {
            echo 'Something went wrong: ' . mysql_error();
        }
    }
    
    function edit_item($table) {
        $id = $_POST['id'];
        $sql = "SELECT id, name, url FROM $table WHERE id = $id";
        $query = mysql_query($sql);

        while($row = mysql_fetch_array($query)) {
        echo '                <form action="admin_manufacturers.php" method="post">
                    <input type="hidden" name="id" value="' . $row['id'] . '">
                    Name: <input type="text" name="name" value="' . $row['name'] . '"><br>
                    URL: <input type="text" name="num" value="' . $row['url'] . '"><br>
                    <input type="submit" name="edit_finish" value="Save data">
                    </form>';
        }
        
    }
    
    function edit_item_finish($table) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $url = $_POST['url'];
        $sql = "UPDATE $table SET name = '$name', url = '$url' WHERE id = $id";
        $query = mysql_query($sql);
        if($query) {
            echo 'Successfully edited item.';
        } else {
            echo 'Something went wrong: ' . mysql_error();
        }
    }
    
    function remove_item() {
        echo'                 Are you sure you want to delete selected item?
                    <form action="admin_manufacturers.php" method="post">
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
    
    function select_function($table) {
        if($_POST['add']) {
            add_item();
        } else if($_POST['edit']) {
            edit_item($table);
        } else if($_POST['remove']) {
            remove_item($table);
        } else if($_POST['add_finish']) {
            add_item_finish($table);
        } else if($_POST['edit_finish']) {
            edit_item_finish($table);
        } else if($_POST['remove_finish']){
            remove_item_finish($table);
        } else {
            list_items($table);
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
                    select_function($manufacturer);
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
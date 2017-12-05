<?php
    include("admin_db.php");
    
    function list_items($table, $mysqli) {
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
        
        $stmt = $mysqli->prepare("SELECT * FROM $table");
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
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
        }
        $stmt->close();
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
    
    function add_item_finish($table, $mysqli) {
        $name = $_POST['name'];
        $url = $_POST['url'];
        
        $stmt = $mysqli->prepare("INSERT INTO $table (name, url) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $url);
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Something went error: ' . $mysqli->error;
        } else {
            echo 'Item has been added.';
        }
        $stmt->close();
    }
    
    function edit_item($table, $mysqli) {
        $id = $_POST['id'];
        
        $stmt = $mysqli->prepare("SELECT id, name, url FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
            echo '                <form action="admin_manufacturers.php" method="post">
                    <input type="hidden" name="id" value="' . $row['id'] . '">
                    Name: <input type="text" name="name" value="' . $row['name'] . '"><br>
                    URL: <input type="text" name="url" value="' . $row['url'] . '"><br>
                    <input type="submit" name="edit_finish" value="Save data">
                    </form>';
            }
        }
        $stmt->close();
        
        
    }
    
    function edit_item_finish($table, $mysqli) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $url = $_POST['url'];
        
        $stmt = $mysqli->prepare("UPDATE $table SET name = ?, url = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $url, $id);
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            echo 'Successfully edited item.';
        }
        $stmt->close();
    }
    
    function remove_item() {
        echo'                 Are you sure you want to delete selected item?
                    <form action="admin_manufacturers.php" method="post">
                    <input hidden="id" name="id" value="' . $_POST['id'] . '">
                    <input type="submit" name="remove_finish" value="Remove">
                    </form>';
    }
    
    function remove_item_finish($table, $mysqli) {
        $id = $_POST['id'];
        
        $stmt = $mysqli->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            echo 'Sucessfully deleted item.';
        }
        $stmt->close();
    }
    
    function select_function($table, $mysqli) {
        $view = isset($_POST['view']);
        $add = isset($_POST['add']);
        $edit = isset($_POST['edit']);
        $remove = isset($_POST['remove']);
        $add_finish = isset($_POST['add_finish']);
        $edit_finish = isset($_POST['edit_finish']);
        $remove_finish = isset($_POST['remove_finish']);
        
        if($add) {
            add_item();
        } else if($edit) {
            edit_item($table, $mysqli);
        } else if($remove) {
            remove_item($table);
        } else if($add_finish) {
            add_item_finish($table, $mysqli);
        } else if($edit_finish) {
            edit_item_finish($table, $mysqli);
        } else if($remove_finish){
            remove_item_finish($table, $mysqli);
        } else {
            list_items($table, $mysqli);
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
                    select_function($manufacturer, $mysqli);
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
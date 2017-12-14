<?php
    
    include("admin_db.php");
    
    function list_items($table, $mysqli) {
        echo '
            <table style="width: 100%;">
                <tr>
                    <th style="text-align: left; width: 50px;">Id</th>
                    <th style="text-align: left; width: 150px;">Name</th>
                    <th style="text-align: left; width: 50px;">Products</th>
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
                    <form action="admin_categories.php" method="post">
                        <input type="hidden" name="id" value="' . $row['id'] . '">
                        <th style="text-align: left;">' . $row['id'] . '</th>
                        <th style="text-align: left;">' . $row['name'] . '</th>
                        <th style="text-align: left;">' . $row['num_Products'] . '</th>
                        <th></th>
                        <th style="text-align: left;"><input type="submit" name="edit" value="Edit category"></th>
                        <th style="text-align: left;"><input type="submit" name="remove" value="Remove category"></th>
                    </form>
                </tr>';
            }
        }
        $stmt->close();
        echo '
                    <tr>
                        <form action="admin_categories.php" method="post">
                        <th><input type="submit" name="add" value="Add category"></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        </form>
                    </tr>
            </table>';
    }
    
    function add_item() {
        echo '                <form action="admin_categories.php" method="post">
                    Name: <input type="text" name="name">
                    <input type="submit" name="add_finish" value="Add item">
                    </form>';
    }
    
    function add_item_finish($table, $mysqli) {
        $name = $_POST['name'];
        $sql = "INSERT INTO $table (name) VALUES ('$name')";
        $query = mysql_query($sql);
        
        $stmt = $mysqli->prepare("INSERT INTO $table (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Someting went wrong: ' . $mysqli->error;
        } else {
            echo 'Item has been added.';
        }
        $stmt->close();
    }
    
    function edit_item($table, $mysqli) {
        $id = $_POST['id'];
        
        $stmt = $mysqli->prepare("SELECT id, name, num_Products FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
        echo '                <form action="admin_categories.php" method="post">
                    <input type="hidden" name="id" value="' . $row['id'] . '">
                    Name: <input type="text" name="name" value="' . $row['name'] . '"><br>
                    Number of products: <input type="text" name="num" value="' . $row['num_Products'] . '"><br>
                    <input type="submit" name="edit_finish" value="Save data">
                    </form>';
        }
        $stmt->close();
    }
    
    function edit_item_finish($table, $mysqli) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $num = $_POST['num'];
        
        $stmt = $mysqli->prepare("UPDATE $table SET name = ?, num_Products = ? WHERE id = ?");
        $stmt->bind_param("sii", $name, $num, $id);
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
                    <form action="admin_categories.php" method="post">
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
            echo 'Successfully removed item.';
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
    

    include("admin_html/admin_upper.html");
    include("admin_html/admin_menu.html");
    include("admin_html/admin_middle.html");
    select_function($category, $mysqli);
    include("admin_html/admin_bottom.html");
?>
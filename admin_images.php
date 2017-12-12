<?php

    include("admin_db.php");

    function list_items($table, $mysqli) {
        echo '<table>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Filename</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>';
        $stmt = $mysqli->prepare("SELECT * FROM $table");
        $stmt->execute();
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                echo '
                <tr>
                    <form action="admin_images.php" method="post">
                    <input type="hidden" name="id" value="' . $row['id'] . '"
                    <th>' . $row['id'] . '</th>
                    <th><a target="_blank" href=img/' . $row['filename'] . '><img src="img/' . $row['filename'] . '" height="100"></a></th>
                    <th>' . $row['imagename'] . '</th>
                    <th>' . $row['filename'] . '</th>
                    <th></th>
                    <th><input type="submit" name="edit" value="Edit image"></th>
                    <th><input type="submit" name="remove" value="Remove image"></th>
                    </form>
                </tr>';
            }
            echo '</table>';
        }
    }
    
    function add_item() {
        
    }
    
    function edit_item($table, $mysqli) {
        
    }
    
    function remove_item() {
        
    }
    
    function add_item_finish($table, $mysqli) {
        
    }
    
    function edit_item_finish($table, $mysqli) {
        
    }
    
    function remove_item_finish($table, $mysqli) {
        
    }
    
    function select_function($table, $table2, $mysqli) {
        $view = isset($_POST['view']);
        $add = isset($_POST['add']);
        $edit = isset($_POST['edit']);
        $remove = isset($_POST['remove']);
        $add_finish = isset($_POST['add_finish']);
        $edit_finish = isset($_POST['edit_finish']);
        $remove_finish = isset($_POST['remove_finish']);
            
        if($add) {
            add_item($table2, $mysqli);
        } else if($edit) {
            edit_item($table, $table2, $mysqli);
        } else if($remove) {
            remove_item();
        } else if($add_finish) {
            add_item_finish($table, $mysqli);
        } else if($edit_finish) {
            edit_item_finish($table, $mysqli);
        } else if($remove_finish){
            remove_item_finish($table, $mysqli);
        } else {
            list_items($table, $table2, $mysqli);
        }
    }
    
    
    
    include("admin_html/admin_upper.html");
    include("admin_html/admin_menu.html");
    include("admin_html/admin_middle.html");
    select_function($images, $mysqli);
    include("admin_html/admin_bottom.html");
?>
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
                    <input type="hidden" name="id" value="' . $row['id'] . '">
                    <th>' . $row['id'] . '</th>
                    <th><a target="_blank" href=' . $row['filename'] . '><img src="' . $row['filename'] . '" height="100"></a></th>
                    <th>' . $row['imagename'] . '</th>
                    <th>' . $row['filename'] . '</th>
                    <th></th>
                    <th><input type="submit" name="edit" value="Edit image"></th>
                    <th><input type="submit" name="remove" value="Remove image"></th>
                    </form>
                </tr>';
            }
            echo '<tr style="height: 20px;">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <form action="admin_images.php" method="post">
                    <th><input type="submit" name="add" value="Add image"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="width: 20px;"></th>
                    <th></th>
                    <th></th>
                    </form>
                </tr>
                </table>';
        }
    }
    
    function add_item() {
        echo '
            <form action="admin_images.php" method="post" enctype="multipart/form-data">
            Imagename:<br>
            <input type="text" name="imagename"><br>
            Image:<br>
            <input type="file" name="imagefile" id="imagefile">
            <input type="submit" value="Upload Image" name="add_finish">
            </form>';
    }
    
    function edit_item($table, $mysqli) {
        
        $id = $_POST['id'];
        
        $stmt = $mysqli->prepare("SELECT * FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                echo '
            <form action="admin_images.php" method="post">
                Imagename:<br>
                <input type="hidden" name="id" value="' . $row['id'] . '"
                <input type="text" name="imagename" value="' . $row['imagename'] . '">
                <input type="submit" name="edit_finish" value="Save data">
            </form>';
            }
            $stmt->close();
        }
        
    }
    
    function remove_item() {
        echo'                 Are you sure you want to delete selected item?
                    <form action="admin_images.php" method="post">
                    <input hidden="id" name="id" value="' . $_POST['id'] . '">
                    <input type="submit" name="remove_finish" value="Remove">
                    </form>';
    }
    
    function add_item_finish($table, $mysqli) {
        $imagename = $_POST['imagename'];
        
        $targetdir = "img/";
        $target_file = $targetdir . $_FILES["imagefile"]["name"];
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["imagefile"]["tmp_name"]);
            if($check !== false) {
                //echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        
         // Check file size
        if ($_FILES["imagefile"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            //$filename = "img/" . $target_file;
            $stmt = $mysqli->prepare("INSERT INTO $table (filename, imagename) VALUES (?, ?)");
            $stmt->bind_param("ss", $target_file, $imagename);
            $stmt->execute();
            if (move_uploaded_file($_FILES["imagefile"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["imagefile"]["name"]). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        $stmt->close();
        
    }
    
    function edit_item_finish($table, $mysqli) {
        $id = $_POST['id'];
        $imagename = $_POST['imagename'];
        
        $stmt = $mysqli->prepare("UPDATE $table SET imagename = ? WHERE id = ?");
        $stmt->bind_param("si", $imagename, $id);
        $stmt->execute();
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            echo 'Sucessfully edited item.';
        }
        $stmt->close();
    }
    
    function remove_item_finish($table, $mysqli) {
        $id = $_POST['id'];
        
        $stmt = $mysqli->prepare("SELECT filename FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            $filename = $row['filename'];
        }
        
        if (file_exists($filename)) {
            unlink($filename);
        } else {
            echo 'Could not delete '.$filename.', file does not exist.<br>';
        }
        
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
            remove_item();
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
    select_function($images, $mysqli);
    include("admin_html/admin_bottom.html");
?>
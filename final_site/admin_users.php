<?php

    include("db.php");
   
    function list_items($table, $mysqli) {
    
        echo '
            <table style="width: 100%;">
                <tr>
                    <th style="text-align: left; width: 50px;">Id</th>
                    <th style="text-align: left; width: 50px;">Email</th>
                    <th style="text-align: left; width: 150px;">Firstname</th>
                    <th style="text-align: left; width: 50px;">Surname</th>
                    <th style="text-align: left; width: 50px;">Orders</th>
                    <th style="text-align: left;">Permission</th>
                    <th style="width: 20px;"></th>
                    <th style="text-align: left; width: 150px;"></th>
                    <th style="text-align: left; width: 150px;"></th>
                    <th style="text-align: left;"></th>
                </tr>';
        $stmt = $mysqli->prepare("SELECT * FROM $table");
        if($stmt->execute()) {
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                echo '                <tr>
                    <form action="admin_users.php" method="post">
                        <input type="hidden" name="id" value="' . $row['id'] . '">
                        <th style="text-align: left;">' . $row['email'] . '</th>
                        <th style="text-align: left;">' . $row['fName'] . '</th>
                        <th style="text-align: left;">' . $row['sName'] . '</th>
                        <th style="text-align: left;">' . $row['orders'] . '</th>';
                        if(isset($row['permissions']) == 1) {
                            echo '
                        <th style="text-align: left;">Admin</th>';
                        } else {
                            echo '
                        <th style="text-align: left;">Customer</th>';
                        }
                        echo '
                        <th></th>
                        <th style="text-align: left;"><input type="submit" name="view" value="View user"></th>
                        <th style="text-align: left;"><input type="submit" name="edit" value="Edit user"></th>
                        <th style="text-align: left;"><input type="submit" name="remove" value="Remove user"></th>
                    </form>
                </tr>';
            }
            $stmt->close();
            echo '
                <tr style="height: 20px;">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <form action="admin_users.php" method="post">
                    <th><input type="submit" name="add" value="Add user"></th>
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
    }
    
    function view_item($table, $mysqli) {
        $id = $_POST['id'];
        $stmt = $mysqli->prepare("SELECT * FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            echo '<table>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Orders</th>
                    <th>Permissions</th>
                    <th>Password</th>
                </tr>
                ';
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                echo '
                <tr>
                    <th>' . $row['id'] . '</th>
                    <th>' . $row['email'] . '</th>
                    <th>' . $row['fName'] . '</th>
                    <th>' . $row['sName'] . '</th>
                    <th>' . $row['orders'] . '</th>';
                    if($row['permission'] == 1) {
                        echo '                    <th>Admin</th>';
                    } else {
                        echo '                    <th>Customer</th>';
                    }
                    echo '
                    <th>' . $row['password'] . '</th>
                </tr>
            </table>
            <table>
                <tr>
                    <th>Address</th>
                    <th>City</th>
                    <th>Postcode</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Phone</th>
                </tr>
                <tr>
                    <th>' . $row['address'] . '</th>
                    <th>' . $row['city'] . '</th>
                    <th>' . $row['postcode'] . '</th>
                    <th>' . $row['state'] . '</th>
                    <th>' . $row['country'] . '</th>
                    <th>' . $row['phone'] . '</th>
                </tr>
            </table>';
            }
        }
        $stmt->close();
        
    }
    
    function add_item() {
        echo '            <form action="admin_users.php" method="post">
                Firstname:<br>
                <input type="text" name="fname"><br>
                Lastname:<br>
                <input type="text" name="sname"><br>
                Email:<br>
                <input type="text" name="email"><br>
                Password:<br>
                <input type="text" name="password"><br>
                Address:<br>
                <input type="text" name="address"><br>
                City:<br>
                <input type="text" name="city"><br>
                Postcode:<br>
                <input type="text" name="postcode"><br>
                State:<br>
                <input type="text" name="state"><br>
                Country:<br>
                <input type="text" name="country"><br>
                Phone:<br>
                <input type="text" name="phone"><br>
                Permissions:<br>
                <select name="permission">
                    <option selected value="0">Customer</option>
                    <option value="1">Admin</option>
                </select>
                <input type="submit" name="add_finish" value="Add user">
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
                echo '            <form action="admin_users.php" method="post">
                <input type="hidden" name="id" value="' . $row['id'] . '">
                Firstname:<br>
                <input type="text" name="fname" value="' . $row['fName'] . '"><br>
                Lastname:<br>
                <input type="text" name="sname" value="' . $row['sName'] . '"><br>
                Email:<br>
                <input type="text" name="email" value="' . $row['email'] . '"><br>
                Password:<br>
                <input type="text" name="password" value="' . $row['password'] . '"><br>
                Address:<br>
                <input type="text" name="address" value="' . $row['address'] . '"><br>
                City:<br>
                <input type="text" name="city" value="' . $row['city'] . '"><br>
                Postcode:<br>
                <input type="" name="postcode" value="' . $row['postcode'] . '"><br>
                State:<br>
                <input type="" name="state" value="' . $row['state'] . '"><br>
                Country:<br>
                <input type="text" name="country" value="' . $row['country'] . '"><br>
                Phone:<br>
                <input type="text" name="phone" value="' . $row['phone'] . '"><br>
                Permissions:<br>
                <select name="permission">';
                if($row['permission'] == 1) {
                    echo '
                    <option value="0">Customer</option>
                    <option selected value="1">Admin</option>';
                } else {
                    echo '
                    <option selected value="0">Customer</option>
                    <option value="1">Admin</option>';
                }
                echo '
                </select>
                <input type="submit" name="edit_finish" value="Save data">
            </form>';
            }
            $stmt->close();
            
        }
    }
    
    function remove_item() {
        echo'                 Are you sure you want to delete selected user?
                    <form action="admin_users.php" method="post">
                    <input hidden="id" name="id" value="' . $_POST['id'] . '">
                    <input type="submit" name="remove_finish" value="Remove">
                    </form>';
    }
    
    function add_item_finish($table, $mysqli) {
        $fname = $_POST['fname'];
        $sname = $_POST['sname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $postcode = $_POST['postcode'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $phone = $_POST['phone'];
        $permission = $_POST['permission'];
        
        $stmt = $mysqli->prepare("INSERT INTO $table (password, email, fName,
         sName, address, city, postcode, state, country, phone, permission)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        }
        $stmt->bind_param("ssssssissii", $password, $email, $fname, $sname,
         $address, $city, $postcode, $state, $country, $phone, $permission);
        $stmt->execute();
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            echo 'User has been added.';
        }
        $stmt->close();
    }
    
    function edit_item_finish($table, $mysqli) {
        $id = $_POST['id'];
        $fname = $_POST['fname'];
        $sname = $_POST['sname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $postcode = $_POST['postcode'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $phone = $_POST['phone'];
        $permission = $_POST['permission'];
        
        $stmt = $mysqli->prepare("UPDATE $table SET password = ?, email = ?, 
         fName = ?, sName = ?, address = ?, city = ?, postcode = ?, state = ?,
         country = ?, phone = ?, permission = ? WHERE id = ?");
         
         $stmt->bind_param("ssssssissiii", $password, $email, $fname, $sname, 
          $address, $city, $postcode, $state, $country, $phone, $permission, $id);
          
         $stmt->execute();
         if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
         } else {
            echo 'Successfully edited user.';
         }
         $stmt->close();
    }
    
    function remove_item_finish($table, $mysqli) {
        $id = $_POST['id'];
        
        $stmt = $mysqli->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            echo 'Sucessfully deleted user.';
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
        
        if($view) {
            view_item($table, $mysqli);
        } else if($add) {
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

    include("store_html/top.html");
    include("store_html/menu.html");
    include("store_html/middle.html");
    select_function($users, $mysqli);
    include("store_html/bottom.html");
?>
<?php
    include("admin_db.php");
   
    function list_items($table, $table2, $mysqli) {
        
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
        $stmt = $mysqli->prepare("SELECT $table.id, $table2.name AS manufacturername, $table.name, 
        $table.cost, $table.stock, $table.manufacturer 
        FROM $table 
        LEFT JOIN $table2 ON $table.manufacturer = $table2.id");
        
        if($stmt->execute()) {
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
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
    
    
    function view_item($table, $table2, $mysqli) {
        $id = $_POST['id'];
        $stmt = $mysqli->prepare("SELECT *, $table.name AS prod_name, $table2.name AS man_name FROM $table 
            LEFT JOIN $table2 ON $table.manufacturer = $table2.id WHERE $table.id = $id");
        $stmt->execute();
        
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
        
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
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
        }
        
        $stmt->close();
        
        echo '</table>';
        
        
    }
    
    function add_item($table, $mysqli) {
        $stmt = $mysqli->prepare("SELECT id, name FROM $table");
        $stmt->execute();
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            $result = $stmt->get_result();
            echo '                <form action="admin_products.php" method="post">
                    Name:<br>
                    <input type="text" name="name"><br>
                    Manufacturer:<br>
                    <select name="manufacturer">
                    ';
            while($row = $result->fetch_assoc()) {
                echo '                      <option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            }
                    
            echo '
                    </select><br>
                    Category: <select name="category">';
        }
        $stmt->close();
        
        $stmt = $mysqli->prepare("SELECT id, name FROM d0018e_categories");
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                echo '                      <option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            }
            echo '
                    </select><br>
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
        $stmt->close();
    }
    
    function add_item_finish($table, $mysqli) {
        $name = $_POST['name'];
        $manufacturer = $_POST['manufacturer'];
        $category_name = $_POST['category'];
        $shortdesc = $_POST['shortdesc'];
        $longdesc = $_POST['longdesc'];
        $cost = $_POST['cost'];
        $stock = $_POST['stock'];
        
        $stmt = $mysqli->prepare("INSERT INTO $table (name, manufacturer, 
        category, shortDesc, longDesc, cost, stock) VALUES (?, ?,
           ? ,?, ?, ?, ?)");
        $stmt->bind_param("ssissii", $name, $manufacturer, $category_name, $shortdesc, $longdesc, $cost, $stock);
        $stmt->execute();
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            echo 'Item has been added.';
        }
        $stmt->close();
    }
    
    function edit_item($table, $table2, $mysqli) {
        $id = $_POST['id'];
        
        $stmt = $mysqli->prepare("SELECT id, name FROM $table2");
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                $manufacturer[$row['name']] = $row['id'];
            }
        }
        $stmt->close();
        
        $stmt = $mysqli->prepare("SELECT id, name FROM d0018e_categories");
        $stmt->execute();
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                $categories[$row['name']] = $row['id'];
            }
        }
        $stmt->close();
        
        $stmt = $mysqli->prepare("SELECT * FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
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
                    Category: <select name="category">';
                    
                    foreach($categories as $cat_name => $cat_id) {
                        if($cat_id == $row['category']) {
                            echo '                      <option selected value="' . $cat_id . '">' . $cat_name . '</option>';
                        } else {
                            echo '                      <option value="' . $cat_id . '">' . $cat_name . '</option>';
                        }
                    }
                    echo '
                    </select><br>
                    Short description: <textarea name="shortdesc" rows="4" cols="50">' . $row['shortDesc'] . '</textarea><br>
                    Long description: <textarea name="longdesc" rows="4" cols="50">' . $row['longDesc'] . '</textarea><br>
                    Cost: <input type="text" name="cost" value="' . $row['cost'] . '"><br>
                    Stock: <input type="text" name="stock" value="' . $row['stock'] . '"><br>
                    <input type="submit" name="edit_finish" value="Save data">
                    </form>';
        }
        $stmt->close();
    }
    
    function edit_item_finish($table, $mysqli) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $manufacturer = $_POST['manufacturer'];
        $category_name = $_POST['category'];
        $shortdesc = $_POST['shortdesc'];
        $longdesc = $_POST['longdesc'];
        $cost = $_POST['cost'];
        $stock = $_POST['stock'];
            
        $stmt = $mysqli->prepare("UPDATE $table SET name = ?, manufacturer = ?, category = ?, shortDesc = ?, 
            longDesc = ?, cost = ?, stock = ? WHERE id = ?");
        $stmt->bind_param("ssissiii", $name, $manufacturer, $category, 
            $shortdesc, $longdesc, $cost, $stock, $id);
        
        $stmt->execute();
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            echo 'Sucessfully edited item.';
        }
        $stmt->close();
    }
    
    function remove_item() {
        echo'                 Are you sure you want to delete selected item?
                    <form action="admin_products.php" method="post">
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
    
    function select_function($table, $table2, $mysqli) {
        $view = isset($_POST['view']);
        $add = isset($_POST['add']);
        $edit = isset($_POST['edit']);
        $remove = isset($_POST['remove']);
        $add_finish = isset($_POST['add_finish']);
        $edit_finish = isset($_POST['edit_finish']);
        $remove_finish = isset($_POST['remove_finish']);
        
        if($view) {
            view_item($table, $table2, $mysqli);
        } else if($add) {
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
    select_function($products, $manufacturer, $mysqli);
    include("admin_html/admin_bottom.html");
?>
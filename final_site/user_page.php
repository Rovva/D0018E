<?php

    include("db.php");

    function list_info($table, $mysqli) {
                
        $id = $_SESSION['user_id'];
        
        $stmt = $mysqli->prepare("SELECT email, fName, sName, address, city, postcode, state, country, phone FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    	$stmt->store_result();
    	$stmt->bind_result($email, $fName, $sName, $address, $city, $postcode, $state, $country, $phone);
    	$stmt->fetch();
        echo '<div class="col-sm-4">
        <table>
            <tr>
                <th colspan="2">Your personal information:</th>
            </tr>
            <tr>
                <th style="text-align: left;">Name:</th>
                <th style="text-align: left;">' . $fName . " " . $sName . '</th>
            </tr>
            <tr>
                <th style="text-align: left;">Email:</th>
                <th style="text-align: left;">' . $email . '</th>
            </tr>
            <tr>
                <th style="text-align: left;">Address:</th>
                <th style="text-align: left;">' . $address . '<br>
                    ' . $postcode . ' ' . $city . '<br>
                    ' . $state . ' ' . $country . '
                </th>
            </tr>
            <tr>
                <th style="text-align: left;">Phone:</th>
                <th style="text-align: left;">' . $phone . '</th>
            </tr>
            <tr>
                <th colspan="2" style="text-align: center;"><a href="user_page.php?action=edit_info">Edit information</a></th>
            </tr>
        </table></div>';
    }
    
    function edit_info($table, $mysqli) {
        $id = $_SESSION['user_id'];
        
        $stmt = $mysqli->prepare("SELECT email, fName, sName, address, city, postcode, state, country,phone FROM $table WHERE id = ?");
        
        $stmt->bind_param("i", $id);
        $stmt->execute();
    	$stmt->store_result();
    	$stmt->bind_result($email, $fName, $sName, $address, $city, $postcode, $state, $country,$phone);
    	$stmt->fetch();
        
        echo '
        <form method="post" action="user_page.php">
        First name:&nbsp;<input type="text" name="fname" value="' . $fName . '"><br>
        Last name:&nbsp;<input type="text" name="sname" value="' . $sName . '"><br>
        Email:&nbsp;<input type="text" name="email" value="' . $email . '"><br>
        Phone:&nbsp;<input type="text" name="phone" value="' . $phone . '"><br>
        Address:&nbsp;<input type="text" name="address" value="' . $address . '"><br>
        Postcode:&nbsp;<input type="text" name="postcode" value="' . $postcode . '"><br>
        City:&nbsp;<input type="text" name="city" value="' . $city . '"><br>
        State:&nbsp;<input type="text" name="state" value="' . $state . '"><br>
        Country:&nbsp;<input type="text" name="country" value="' . $country . '"><br>
        <input type="submit" name="save_info" value="Save">';
    }

    function save_info($table, $mysqli) {
        $fname = $_POST['fname'];
        $sname = $_POST['sname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $postcode = $_POST['postcode'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        
        $id = $_SESSION['user_id'];
        
        $stmt = $mysqli->prepare("UPDATE $table SET fName = ?, sName = ?, email = ?, phone = ?,
         address = ?, postcode = ?, city = ?, state = ?, country = ? WHERE id = ?");
        $stmt->bind_param("sssisisssi", $fname, $sname, $email, $phone, $address, $postcode, $city, $state, $country, $id);
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            echo 'Your updated information has been saved.';
        }
        
    }
    
    function list_orders() {
        
    }
    
    function view_order($table,$mysqli) {
        $stmt= $mysqli->prepare("SELECT id,status,order_date,shipped_date,cost from d0018e_orders WHERE user = ?");
        $stmt->bind_param('s',$_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($id,$status,$order_date,$shipped_date,$cost);
        $stmt->fetch();
        echo '<div class="col-sm-4">
        <table>
        <thead>
        <tr>
        <th>Id</th>
        <th>Status </th>
        <th>Order_date </th>
        <th>Shipped_date </th>
        <th>Cost</th>
        </tr>
        </thead>
        <tbody>
        <tr>
        <td>'.$id.'</td>
        <td>'.$status.'</td>
        <td>'.$order_date.'</td>
        <td>'.$shipped_date.'</td>
        <td>'.$cost.'</td>
        </tr>
        </tbody>
        </table>';
    }
    
    function change_password() {
        
    }
    
    function save_new_password() {
        
    }
    
    function select_function($table, $mysqli) {
        $action = isset($_GET['action']);
        
        $save_info = isset($_POST['save_info']);
        
        if($action == "edit_info") {
            edit_info($table, $mysqli);
        } else if ($save_info) {
            save_info($table, $mysqli);
        } else {
            list_info($table, $mysqli);
            view_order($table,$mysqli);
        }
    }
    
    include("store_html/top.html");
    include("store_html/menu.html");
    include("store_html/middle.html");

    select_function($users, $mysqli);

    include("store_html/bottom.html");
?>
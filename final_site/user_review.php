<?php

    include("db.php");
    
    function make_review($product_id) {
        $get = $_GET['review'];
            
        echo '<div style="margin-left: 10px;"><form action="user_review.php" method="post">
            From a value of 1 to 10, how much would you grade this product?<br>
            <select name="rating">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select><br>
            Please describe what you think about the product:<br>
            <textarea name="text"></textarea><br>
            <input type="hidden" name="product_id" value="' . $get . '">
            <input type="submit" name="save_review" value="Send">
            </form>
            </div>';
        
    }
    
    function save_review($user, $mysqli) {
        $product_id = $_POST['product_id'];
        $rating = $_POST['rating'];
        $text = $_POST['text'];
        
        $stmt = $mysqli->prepare("INSERT INTO d0018e_reviews (user, product, rating, review) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $user, $product_id, $rating, $text);
        $stmt->execute();
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            echo 'Your review has been saved!';
        }
    }
    
    function select_function($user_id, $mysqli) {
        $product_id = isset($_GET['review']);
        $save = isset($_POST['save_review']);
        
        if($product_id) {
            if(already_reviewed($user_id, $product_id, $mysqli)) {
                echo 'You can only review once per product.';
            } else {
                make_review($product_id);
            }
        } else if($save) {
            save_review($user_id, $mysqli);
        }
    }
    
    function already_reviewed($user_id, $product, $mysqli) {
        $stmt = $mysqli->prepare("SELECT user FROM d0018e_reviews WHERE product = ?");
        $stmt->bind_param("i", $product);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($rev_user);
        $stmt->fetch();
        echo $rev_user;
        echo $user_id;
        if($rev_user == $user_id) {
            return true;
        } else {
            return false;
        }
        
    }

    include("store_html/top.html");
    include("store_html/menu.html");
    include("store_html/middle.html");
    if(login_check()) {
        $user_id = $_SESSION['user_id'];
        select_function($user_id, $mysqli);        
    } else {
        echo 'You must have an account and be logged in to write a review!';
    }
    include("store_html/bottom.html");
    
?>
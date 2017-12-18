<?php

    include("db.php");

    include("store_html/top.html");
    include("store_html/menu.html");
    include("store_html/middle.html");
    

    $get = $_GET['id'];
               
           $stmt = $mysqli->prepare("SELECT * FROM $images");
           $stmt->execute();
            
           $result = $stmt->get_result();
           while($row = $result->fetch_assoc()) {
               $images_array[$row['filename']] = $row['id'];
           }
           
           $stmt = $mysqli->prepare("SELECT $products.id, $manufacturer.name AS manufacturername, $products.name, 
           $products.longdesc, $products.cost, $products.stock, $products.manufacturer, $products.image 
           FROM $products 
           LEFT JOIN $manufacturer ON $products.manufacturer = $manufacturer.id WHERE $products.id = ?");
           $stmt->bind_param("i", $get);
           $stmt->execute();
            
           $result = $stmt->get_result();
           $product_id;
           while ($row = $result->fetch_assoc()) {
                $product_id = $row['id'];
                $filename = "";
                foreach($images_array as $img_name => $img_id) {
                    if($img_id == $row['image']) {
                        $filename = '<img width="210px" src="' . $img_name . '">';
                    }
                }
                
                echo '
                    <form action="ServerSide/addtocart.php">
                        <div class="productdetail_box">
                            <div class="productdetail_header">' . $row['manufacturername'] . ' - ' . $row['name'] . '</div>
                            <div class="productdetail_img">' . $filename . '<br>Rating:</div>
                            <div class="productdetail_desc">' . $row['longdesc'] . '</div>
                            <div class="productdetail_bottom"><div class="productdetail_stock">' . $row['stock'] . ' in stock</div><div class="productdetail_price">' . $row['cost'] . ':-
                            <button type="submit" name="btnValue" id="btnValue" class="product_buy" value='. $row['id'] .' >Buy</div></div>
                        </div>
                    </form>';
                echo '<div class="productdetail_reviews">Reviews:<br>';
           
                $rating_stmt = $mysqli->prepare("SELECT d0018e_reviews.rating AS rating, d0018e_reviews.review AS review, 
                 d0018e_users.email AS user_email FROM d0018e_reviews LEFT JOIN d0018e_users ON
                 d0018e_reviews.user = d0018e_users.id WHERE product = ?");
                
                $rating_stmt->bind_param("i", $product_id);
                if($rating_stmt->execute()) {
                    $rating_result = $rating_stmt->get_result();
                    while($review_row = $rating_result->fetch_assoc()) {
                    
                        echo '<div class="review_email">User: ' . $review_row['user_email'] . '</div>
                        <div class="review_rating">Rating: ' . $review_row['rating'] . '</div>
                        <div class="review_text">' . $review_row['review'] . '</div>';
                    }
                } else {
                    echo 'There is no reviews on this product.';
                }
               echo '<br><div class="productdetail_review_it"><a href="user_review.php?review=' . $row['id'] . '">Review this product!</a></div>';
               echo '</div>';
           }
    include("store_html/bottom.html");
           
?>
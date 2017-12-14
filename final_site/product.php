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
           while ($row = $result->fetch_assoc()) {
                $filename = "";
                foreach($images_array as $img_name => $img_id) {
                    if($img_id == $row['image']) {
                        $filename = '<img width="210px" src="' . $img_name . '">';
                    }
                }
                echo '
                    <form action="addtocart.php">
                        <div class="productdetail_box">
                            <div class="productdetail_header">' . $row['manufacturername'] . ' - ' . $row['name'] . '</div>
                            <div class="productdetail_img">' . $filename . '<br>Rating:</div>
                            <div class="productdetail_desc">' . $row['longdesc'] . '</div>
                            <div class="productdetail_bottom"><div class="productdetail_stock">' . $row['stock'] . ' in stock</div><div class="productdetail_price">' . $row['cost'] . ':- <input type="button" value="Buy"></div></div>
                        ';
           }
           echo '<div class="productdetail_reviews">Reviews:</div>';
           
           $stmt = $mysqli->prepare("SELECT * FROM d0018e_reviews WHERE product = ?");
           $stmt->bind_param("i", $get);
           $stmt->execute();
           
           if(!$stmt) {
               echo 'Something went wrong: ' . $mysqli->error;
           } else {
               $result = $stmt->get_result();
               while($row = $result->fetch_assoc()) {
                   echo 'blabla';
               }
           }
           
           echo '</div>
                    </form>';
    include("store_html/bottom.html");
           
?>
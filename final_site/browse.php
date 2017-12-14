<?php

    include("db.php");

    include("store_html/top.html");
    include("store_html/menu.html");
    include("store_html/middle.html");
    $get = $_GET['cat'];
            
        $stmt = $mysqli->prepare("SELECT * FROM d0018e_images");
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            $images_array[$row['filename']] = $row['id'];
        }
            
            $stmt = $mysqli->prepare("SELECT $products.id, $manufacturer.name AS manufacturername, $products.name, 
            $products.shortdesc, $products.cost, $products.stock, $products.manufacturer, $products.image 
            FROM $products 
            LEFT JOIN $manufacturer ON $products.manufacturer = $manufacturer.id WHERE category = ?");
            $stmt->bind_param("i", $get);
            $stmt->execute();
            
            if(!$stmt) {
                echo 'Something went wrong: ' . $mysqli->error;
            } else {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    
                    $filename = "";
                    foreach($images_array as $img_name => $img_id) {
                        if(!$row['image']) {
                                //echo 'No image';
                        } else {
                            if($img_id == $row['image']) {
                                $filename = '<img src="' . $img_name . '" class="thumb">';
                            }
                        }
                    }
                    echo '      <form action="ServerSide/addtocart.php">
                                  <div class="product_box">
                                      <div class="product_img">' . $filename . '</div>
                                      <div class="product_text">
                                          <div class="product_header"><a class="product_link" href="product.php?id=' . $row['id'] . '">' . $row['manufacturername'] . ' - ' . $row['name'] . '</a></div>
                                          <div class="product_desc">' . $row['shortdesc'] . '</div>
                                          <div class="product_available">' . $row['stock'] . ' in stock</div>
                                          <div class="product_id">Product number. ' . $row['id'] . '</div>
                                      </div>
                                      <div class="product_price">' . $row['cost'] . ':-&nbsp;<button type="submit" name="btnValue" id="btnValue" class="product_buy" value='. $row['id'] .' >Buy</div>
                                  </div>
                              </form>';
                    }
            }
            $stmt->close();
            include("store_html/bottom.html");
?>
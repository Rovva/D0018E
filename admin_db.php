<?php

    $mysqli = new mysqli("localhost", "skola", "skola", "skola");
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    
    $mysqli->set_charset("utf8");
    
    $category = "d0018e_categories";
    $orders = "d0018e_orders";
    $products = "d0018e_products";
    $carts = "d0018e_carts";
    $manufacturer = "d0018e_manufacturers";
    $users = "d0018e_users";
    $images = "d0018e_images";
?>
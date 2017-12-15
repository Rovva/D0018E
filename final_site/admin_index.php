<?php

    include("db.php");
    
    include("store_html/top.html");
    include("store_html/menu.html");
    include("store_html/middle.html");
    select_function($images, $mysqli);
    echo 'Welcome to the adminpage!';
    include("store_html/bottom.html");
    
?>
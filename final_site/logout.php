<?php

    include("db.php");

    include("store_html/top.html");
    include("store_html/menu.html");
    include("store_html/middle.html");
    
    // Unset all session values
    $_SESSION = array();
    // get session parameters 
    $params = session_get_cookie_params();
    // Delete the actual cookie.
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    // Destroy session
    session_destroy();
    
    echo 'You have been logged out.';
    include("store_html/bottom.html");
    header('Refresh: 3; URL=./');

?>
<?php

define("HOST", "localhost"); // The host you want to connect to.
define("USER", "root"); // The database username.
define("PASSWORD", ""); // The database password. 
define("DATABASE", "skola"); // The database name.
 
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
  if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
  }
// If you are connecting via TCP/IP rather than a UNIX socket remember to add the port number as a parameter.

?>
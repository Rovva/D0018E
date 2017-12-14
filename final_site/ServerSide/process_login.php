<?php
include 'functions.php';
sec_session_start(); 
 
if(isset($_POST['email'], $_POST['password'])) { 
   $email = $_POST['email'];
   $password = $_POST['password']; 
   if(login($email, $password) == true) {
      // Login success
      header("Location: ../login.php?login_success=1");
      exit();
   } else {
      // Login failed
     header("Location: ../login.php?error=1");
exit();
   }
} else { 
   // The correct POST variables were not sent to this page.
   echo 'Invalid Request';
}


;?>
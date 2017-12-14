<?php
function login_check() {
   // Check if all session variables are set
  $mysqli = new mysqli("localhost", "skola", "skola", "skola");
    if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
  }
   if(isset($_SESSION['user_id'], $_SESSION['email'], $_SESSION['login_string'])) {
     $user_id = $_SESSION['user_id'];
     $login_string = $_SESSION['login_string'];
     $ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user. 
     $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
     if ($stmt = $mysqli->prepare("SELECT password FROM d0018e_users WHERE id = ? LIMIT 1")) { 
        $stmt->bind_param('s', $user_id); // Bind "$user_id" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();
        if($stmt->num_rows == 1) { // If the user exists
           $stmt->bind_result($password); // get variables from result.
           $stmt->fetch();
           $login_check = hash('sha512', $password.$ip_address.$user_browser);
           if($login_check == $login_string) {
              // Logged In!!!!
              return true;
           } else {
              // Not logged in
              return false;
           }
        } else {
            // Not logged in
            return false;
        }
     } else {
        // Not logged in
        return false;
     }
   } else {
     // Not logged in
     return false;
   }
}
function sec_session_start() {
        $session_name = 'sec_session_id'; // Set a custom session name
        $secure = false; // Set to true if using https.
        $httponly = true; // This stops javascript being able to access the session id. 
 
        ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
        $cookieParams = session_get_cookie_params(); // Gets current cookies params.
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
        session_name($session_name); // Sets the session name to the one set above.
        session_start(); // Start the php session
        session_regenerate_id(true); // regenerated the session, delete the old one.     
}

function login($email,$password){
  $mysqli = new mysqli("localhost", "skola", "skola", "skola");
  $email = $_POST['email'];
  $password = $_POST['password'];
  if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
  }
  if($stmt = $mysqli->prepare("SELECT id, email, password FROM d0018e_users WHERE email = ? LIMIT 1")){
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $email, $db_password);
    $stmt->fetch();
    if($db_password == $password) { // Check if the password in the database matches the password the user submitted. 
              // Password is correct!
   
                 $ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user. 
                 $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
                 $_SESSION['user_id'] = $user_id; 
                 $_SESSION['email'] = $email;
                 $_SESSION['login_string'] = hash('sha512', $password.$ip_address.$user_browser);
                 // Login successful.
                 return true;    
           } else {
              return false;
           }
  }
}
?>
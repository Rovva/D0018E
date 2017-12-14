<?php

    include("db.php");
    if(login_check() == !true){ 
        include("store_html/top.html");
        include("store_html/menu.html");
        include("store_html/middle.html");
    
?>
           <div class="loginbox">
		      <form action="ServerSide/process_login.php" method="post" name="login_form">
            <label for="inputEmail">Email</label><br>
            <input type="text" id="email" name="email"placeholder="Email"><br>
            <label for="inputPassword">Password</label><br>
            <input type="password" name="password" id="password" placeholder="Password"><br>
           <button type="submit">Login</button>
              </form>
           </div>

<?php 

        include("store_html/bottom.html");

    } else {
        header("Location: ./user_page.php");
    }

?>
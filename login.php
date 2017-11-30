<?php
include 'ServerSide/functions.php';
sec_session_start();
if(login_check() == !true){ ?>
<!DOCTYPE html>
<html>
<title>Basic frontpage design</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="dropdown.css">
<body>

<div class="main">

	<div class="banner">
	</div>
	
    <div class="navigationbar">
    
        <div class="navigationbar_dropdowns">
        
            <div class="dropdown">
              <button class="firstdropbtn"><a class="firstdropbtn" href="index.html">Home</a></button>
            </div>
            
            <div class="dropdown">
              <button class="dropbtn">Categories</button>
              <div class="dropdown-content">
                <a href="mouse.html">Mouse</a>
              </div>
            </div>
            
            <div class="dropdown">
              <button class="dropbtn"><a class="dropbtn" href="about.html">About ous</a></button>
            </div>
            
            <div class="dropdown">
              <button class="dropbtn"><a class="dropbtn" href="contact.html">Contact</a></button>
            </div>
            
            
            <div class="dropdown">
              <button class="dropbtn"><a class="dropbtn" href="login.html">Login</a></button>
              <div class="dropdown-content">
                <a href="register.php">Register</a>
              </div>
            </div>
        
        </div>        
    </div>
    
	<div class="mainbody">
    
        <div class="mainborders">
        
           <p class="mainbody_text">
           <div class="loginbox">
		      <form action="ServerSide/process_login.php" method="post" name="login_form">
            <label for="inputEmail">Email</label>
            <input type="text" id="email" name="email"placeholder="Email">
            <label for="inputPassword">Password</label>
            <input type="password" name="password" id="password" placeholder="Password">
           <button type="submit">Login</button>
              </form>
           </div>
	       </p>
        
        </div>
    
	</div>
	
	<div class="footer">
		<p class="copyright_text"> Christoffer Rova & Simon Herbertsson &copy; 2017</p>
	</div>

</div>

</body>
</html>
<?php } else {
header("Location: ./user_page.php");;
}

;?>
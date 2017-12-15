<?php
	if(isset($_GET['exists'])) {
		echo 'User already exists';
	} else if(isset($_GET['success'])) {
		echo 'User registered';
	} else if (isset($_GET['registrationfailed'])) {
		echo 'Something went wrong';
	}
    
    
    include("db.php");

    include("store_html/top.html");
    include("store_html/menu.html");
    include("store_html/middle.html");
?>
	<form action="ServerSide/register.php" method="post" name="registration_form">
		<label for="inputEmail">Email</label><br>
			<input type="text" id="email" name="email"placeholder="Email"><br>
		<label for="firstName">First Name</label><br>
			<input type="text" id="fName" name="fName"placeholder="First name"><br>
		<label for="lastName">Last name</label><br>
			<input type="text" id="sName" name="sName"placeholder="Last name"><br>
		<label for="inputPassword">Password</label><br>
			<input type="password" name="password" id="password" placeholder="Password"><br>
		<button type="submit">Register</button><br>
	</form>
<?php
    include("store_html/bottom.html");
?>
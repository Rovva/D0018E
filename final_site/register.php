<?php
	if(isset($_GET['exists'])){
		echo 'User already exists';
	}else if(isset($_GET['success'])){
		echo 'User registered';
	}else if (isset($_GET['registrationfailed'])){
		echo 'Something went wrong';
	}
?>
<html>
	<form action="ServerSide/register.php" method="post" name="registration_form">
		<label for="inputEmail">Email</label>
			<input type="text" id="email" name="email"placeholder="Email">
		<label for="firstName">First Name</label>
			<input type="text" id="fName" name="fName"placeholder="First name">
		<label for="lastName">Last name</label>
			<input type="text" id="sName" name="sName"placeholder="Last name">
		<label for="inputPassword">Password</label>
			<input type="password" name="password" id="password" placeholder="Password">
		<button type="submit">Register</button>
	</form>
</html>


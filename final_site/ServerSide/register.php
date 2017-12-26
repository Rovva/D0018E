<?php
$mysqli = new mysqli("localhost", "skola", "skola", "skola");
$mysqli->set_charset("utf8");
$password = $_POST['password'];
$email = $_POST['email'];
$fName = $_POST['fName'];
$sName = $_POST['sName'];
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
//check if email exists
if ($stmt = $mysqli->prepare("SELECT id FROM d0018e_users WHERE email=? ")){
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id);
	$stmt->fetch();
	if(empty($id)){ //if it doesnt exist do this
		if ($insert_stmt = $mysqli->prepare("INSERT INTO d0018e_users (email, password, fName, sName) VALUES (?,?,?,?)"))
		{
			$insert_stmt->bind_param('ssss', $email, $password, $fName, $sName);
			$insert_stmt->execute();
			header("Location: ../register.php?success=1");
		}
		else{
			header("Location: ../register.php?registrationfailed=1");
		}
	}else{
		header("Location: ../register.php?exists=1");
	}
}

?>
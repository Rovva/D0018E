<?php
$mysqli = new mysqli("localhost", "root", "", "skola");
$mysqli->set_charset("utf8");
$password = $_POST['password'];
$email = $_POST['email'];
$fName = $_POST['fName'];
$sName = $_POST['sName'];
if ($stmt = $mysqli->prepare("SELECT id FROM d0018e_users WHERE email=? ")){
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id);
	$stmt->fetch();
	if(empty($id)){
		if ($insert_stmt = $mysqli->prepare("INSERT INTO d0018e_users (email, password, fName, sName) VALUES (?,?,?,?)"))
		{
			$insert_stmt->bind_param('ssss', $email, $password, $fName, $sName);
			$insert_stmt->execute();
			header("Location: ../?success=1");
		}
		else{
			header("Location: ../?registrationfailed=1");
		}
	}else{
		header("Location: ../?exists=1");
	}
}

?>
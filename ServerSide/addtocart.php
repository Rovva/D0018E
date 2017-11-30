<?php
include 'functions.php';
sec_session_start();
if(login_check() == true){
	//if(isset($_POST['action'])){
		$val=$_POST['btnValue'];//$_REQUEST['data'];
		$user_id = $_SESSION['user_id'];
		$mysqli = new mysqli("localhost", "root", "", "skola");
  if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
  }
		if($select_stmt = $mysqli->prepare("SELECT id, cost FROM d0018e_products WHERE id = ? LIMIT 1")){
			$select_stmt->bind_param('s', $val);
			$select_stmt->execute();
			$select_stmt->store_result();
			$select_stmt->bind_result($prod_id, $prod_cost);
			$select_stmt->fetch();
		}//select product
		if ($stmt_prod = $mysqli->prepare("SELECT id FROM d0018e_carts WHERE user_id = ? LIMIT 1")){
			$stmt_prod->bind_param('s', $user_id);
			$stmt_prod->execute();
			$stmt_prod->store_result();
			$stmt_prod->bind_result($cart_id);
			echo $cart_id;
			echo $prod_id;
			$stmt_prod->fetch();
			if($stmt_prod->num_rows == 1){ //If there exists one cart
				if($stmt_cart_details = $mysqli->prepare("INSERT INTO d0018e_cart_details (cart, prod, quantity) VALUES (?,?,?)")){
						$number1=1;
					$stmt_cart_details->bind_param('sss', $cart_id, $prod_id, $number1);
					$stmt_cart_details->execute();
			}//num rows
		}else {//select cart
			if($stmt_cart = $mysqli->prepare("INSERT INTO d0018e_carts (user_id) VALUES (?)")){
				$stmt_cart->bind_param('s',$user_id);
				$stmt_cart->execute();
				if($stmt_cart_select = $mysqli->prepare("SELECT id FROM d0018e_carts WHERE user_id = ? LIMIT 1")){
					$stmt_cart_select->bind_param('s',$user_id);
					$stmt_cart_select->execute();
					$stmt_cart_select->store_result();
					$stmt_cart_select->bind_result($cart_id);
					$stmt_cart_select->fetch();
				}//get cart
				if($stmt_cart_details = $mysqli->prepare("INSERT INTO d0018e_cart_details (cart, prod, quantity) VALUES (?,?,?)")){
						$number1=1;
					$stmt_cart_details->bind_param('sss', $cart_id, $prod_id, $number1);
					$stmt_cart_details->execute();
					if($stmt_cart_user = $mysqli->prepare("UPDATE d0018e_users SET cart_id = ? WHERE id = ?")){
						$stmt_cart_user->bind_param('ss', $cart_id, $user_id);
						$stmt_cart_user->execute();
					}
				}//create cart details
			}//create cart
		}
	}//Post action isset
//}else{
	//return false;
}
?>
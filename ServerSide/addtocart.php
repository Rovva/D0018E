<?php
include 'functions.php';
sec_session_start();
if(login_check() == true){
	//if(isset($_POST['action'])){
		$val=$_POST['btnValue'];//$_REQUEST['data'];
		$user_id = $_SESSION['user_id'];
		$mysqli = new mysqli("localhost", "root", "", "skola");
		if($select_stmt = $mysqli->prepare("SELECT id, cost FROM d0018e_products WHERE id = ? LIMIT 1")){
			$select_stmt->bind_param('s', $val);
			$select_stmt->execute();
			$select_stmt->store_result();
			$select_stmt->bind_result($prod_id, $prod_cost);
			$select_stmt->fetch();
			$select_stmt->close();
		}//select product
		if ($stmt_prod = $mysqli->prepare("SELECT id FROM d0018e_carts WHERE user_id = ? LIMIT 1")){ //Get id of cart from user id
			$stmt_prod->bind_param('s', $user_id);
			$stmt_prod->execute();
			$stmt_prod->store_result();
			$stmt_prod->bind_result($cart_id);
			$stmt_prod->fetch();
			if($stmt_prod->num_rows == 1){ //If there exists one cart
				$_SESSION['cart_id']=$cart_id;
				if($stmt_cart_details_prod = $mysqli->prepare("SELECT quantity, id FROM d0018e_cart_details WHERE cart = ? AND prod = ?")){
					$stmt_cart_details_prod->bind_param('ss', $cart_id, $prod_id);
					$stmt_cart_details_prod->execute();
					$stmt_cart_details_prod->store_result();
					$stmt_cart_details_prod->bind_result($quantity,$id);
					$stmt_cart_details_prod->fetch();
					if($stmt_cart_details_prod->num_rows == 1){
						$quantity=$quantity+1;
						if($stmt_update_cart_details = $mysqli->prepare("UPDATE d0018e_cart_details SET quantity = ? WHERE id = ?")){
							$stmt_update_cart_details->bind_param('ss', $quantity, $id);
							$stmt_update_cart_details->execute();
							$stmt_update_cart_details->close();
						}
					}else{
						if($stmt_cart_details = $mysqli->prepare("INSERT INTO d0018e_cart_details (cart, prod, quantity) VALUES (?,?,?)")){
							$stmt_prod->close();
							$number1=1;
							$stmt_cart_details->bind_param('sss', $cart_id, $prod_id, $number1);
							$stmt_cart_details->execute();
							$stmt_cart_details->close();
					}
				}

			}//num rows
		}else {//select cart
			if($stmt_cart = $mysqli->prepare("INSERT INTO d0018e_carts (user_id) VALUES (?)")){ //If no cart exists, create cart
				$stmt_cart->bind_param('s',$user_id);
				$stmt_cart->execute();
				$stmt_cart->close();
				if($stmt_cart_select = $mysqli->prepare("SELECT id FROM d0018e_carts WHERE user_id = ? LIMIT 1")){ //Get cart created
					$stmt_cart_select->bind_param('s',$user_id);
					$stmt_cart_select->execute();
					$stmt_cart_select->store_result();
					$stmt_cart_select->bind_result($cart_id);
					$stmt_cart_select->fetch();
					$_SESSION['cart_id']=$cart_id;
					$stmt_cart_select->close();
				}//get cart
				if($stmt_cart_details = $mysqli->prepare("INSERT INTO d0018e_cart_details (cart, prod, quantity) VALUES (?,?,?)")){
					$number1=1;
					$stmt_cart_details->bind_param('sss', $cart_id, $prod_id, $number1);
					$stmt_cart_details->execute();
					$stmt_cart_details->close();
					if($stmt_cart_user = $mysqli->prepare("UPDATE d0018e_users SET cart_id = ? WHERE id = ?")){
						$stmt_cart_user->bind_param('ss', $cart_id, $user_id);
						$stmt_cart_user->execute();
						$stmt_cart_user->close();
					}
				}//create cart details
			}//create cart
		}
	}//Post action isset
//}else{
	//return false;
}
header("Location: ../");
?>
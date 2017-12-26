<?php
include ("db.php");
include("store_html/top.html");
include("store_html/menu.html");
include("store_html/middle.html");
$stmt = $mysqli->prepare("SELECT d0018e_cart_details.id, d0018e_products.name, d0018e_products.cost, d0018e_cart_details.quantity FROM d0018e_cart_details INNER JOIN d0018e_products ON d0018e_products.id = d0018e_cart_details.prod WHERE d0018e_cart_details.cart=?");
$stmt->bind_param('s',$_SESSION['cart_id']);
$stmt->execute();
$result = $stmt->get_result();
echo "<table>";
echo "<thead><tr><th>ProductName</th><th>Cost</th><th>Amount</th><th></th></tr></thead>";
echo "<tbody><tr>";
while($row = $result->fetch_assoc()){
	echo "<tr><td>".$row['name']."</td>" ."<td>". $row['cost']."</td>" ."<td>" .$row['quantity']."</td>
		<td><form action='transactionPage.php' method='post'><input hidden='id' name='id' value=".$row['id']."><button name='minus' value=".$row['quantity'].">-</form></td>
	 	<td><form action='transactionPage.php' method='post'><input hidden='id' name='id' value=".$row['id']."><button name='plus' value=".$row['quantity'].">+</form></td>
	 	<td><form action='transactionPage.php' method='post'><input hidden='id' name='id' value=".$row['id']."><button name='remove' value=".$row['quantity'].">DELETE</form></td></tr>";
}
echo "</tbody></table>";
$stmt->close();
echo "<form action='checkOutPage.php' method='post'><button name='checkout'>CheckOut</button></form>";
if(login_check()==true){
	//If user wishes to reduce quantity of products
	if(ISSET($_POST['minus'])){	
		$id = $_POST['id'];
			$stmtquantity = $mysqli->prepare("SELECT quantity FROM d0018e_cart_details WHERE id = ?");
			$stmtquantity->bind_param('s',$id);
			$stmtquantity->execute();
			$stmtquantity->store_result();
			$stmtquantity->bind_result($quantity);
			$stmtquantity->fetch();
			$stmtquantity->close();
			if($quantity!=0){
				$stmt = $mysqli->prepare("UPDATE d0018e_cart_details SET quantity = ? WHERE id = ?");
				$quantity = $quantity - 1;
				$stmt->bind_param('ss',$quantity,$id);
				$stmt->execute();
				//If quantity of a product reaches 0, just delete it
			}else if($quantity==0){
				$stmtdelete = $mysqli->prepare("DELETE FROM d0018e_cart_details WHERE id=?");
				$stmtdelete->bind_param('s',$id);
				$stmtdelete->execute();
			}
	}else if(ISSET($_POST['plus'])){
			$id = $_POST['id'];
			$stmtquantity = $mysqli->prepare("SELECT quantity FROM d0018e_cart_details WHERE id = ?");
			$stmtquantity->bind_param('s',$id);
			$stmtquantity->execute();
			$stmtquantity->store_result();
			$stmtquantity->bind_result($quantity);
			$stmtquantity->fetch();
			$stmtquantity->close();
			$stmt = $mysqli->prepare("UPDATE d0018e_cart_details SET quantity = ? WHERE id = ?");
			$quantity = $quantity + 1;
			$stmt->bind_param('ss',$quantity,$id);
			$stmt->execute();
			

	}else if(isset($_POST['remove'])){
			$id = $_POST['id'];
			$stmtdelete = $mysqli->prepare("DELETE FROM d0018e_cart_details WHERE id=?");
			$stmtdelete->bind_param('s',$id);
			$stmtdelete->execute();
		
	}
}
include("store_html/bottom.html");

?>

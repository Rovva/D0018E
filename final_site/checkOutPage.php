<?php
include ("db.php");
include("store_html/top.html");
include("store_html/menu.html");
include("store_html/middle.html");
if(isset($_POST['checkout'])){
		//I don't understand how this work, but it does, and I'll probably never manage to make it work again
	$stmt = $mysqli->prepare("SELECT d0018e_cart_details.id, d0018e_products.name, d0018e_products.cost, d0018e_cart_details.quantity FROM d0018e_cart_details INNER JOIN d0018e_products ON d0018e_products.id = d0018e_cart_details.prod WHERE d0018e_cart_details.cart=?");
	$stmt->bind_param('s',$_SESSION['cart_id']);
	$stmt->execute();
	$result = $stmt->get_result();
	echo "<table>";
	echo "<thead><tr><th>ProductName</th><th>Cost</th><th>Amount</th><th></th></tr></thead>";
	echo "<tbody><tr>";
	$cost = 0; 
	while($row = $result->fetch_assoc()){
		$cost = $cost+$row['cost']*$row['quantity'];
		echo "<tr><td>".$row['name']."</td>" ."<td>". $row['cost']."</td>" ."<td>" .$row['quantity']."</td></tr>";
	}
	echo "<tr><td>Total cost</td><td></td><td>".$cost."</td></tr>";
	echo "<tr><td><form action='checkOutPage.php' method='post'><input hidden='cost' name='cost' value=".$cost."><button name='completeOrder'>Complete Order</button></form></td></tr>";
echo "</tbody></table>";
$stmt->close();
}
if(isset($_POST['completeOrder'])){
	$currentCost = $_POST['cost'];
	//same
	$stmt = $mysqli->prepare("SELECT d0018e_products.id, d0018e_products.cost, d0018e_cart_details.quantity FROM d0018e_cart_details INNER JOIN d0018e_products ON d0018e_products.id = d0018e_cart_details.prod WHERE d0018e_cart_details.cart=?");
	$stmt->bind_param('s',$_SESSION['cart_id']);
	$stmt->execute();
	$result = $stmt->get_result();
	$dbCost=0;
	$nothing = 0; //This makes sense
	//Calculate the cost of product from the view of the DB.
	while ($row = $result->fetch_assoc()){
		$dbCost=$dbCost+$row['quantity']*$row['cost'];
	}
	//Check if the DB cost and the cost shown on the page is the same
	if($dbCost==$currentCost && $dbCost != 0){
		//Create an entry in order table
		if($stmtorders = $mysqli->prepare("INSERT INTO d0018e_orders (user, status, cost) VALUES (?,?,?)")){
			$stmtorders->bind_param('sss',$_SESSION['user_id'],$nothing,$currentCost);
			$stmtorders->execute();
			$stmtorders->close();
			$stmtselect = $mysqli->prepare("SELECT id from d0018e_orders WHERE user = ?");
			$stmtselect->bind_param("s",$_SESSION['user_id']);
			$stmtselect->execute();
			$stmtselect->store_result();
			$stmtselect->bind_result($order_id);
			$stmtselect->fetch();
			$stmtselect->close();
			$result->data_seek(0);
			//Create the content of orders
			$stmtinsert =$mysqli->prepare("INSERT INTO d0018e_order_details (order_id,prod_id,quantity,cost) VALUES (?,?,?,?)");
			//Loop to execute previous statement
			while ($row = $result->fetch_assoc()){
				$stmtinsert->bind_param('ssss',$order_id,$row['id'],$row['quantity'],$row['cost']);
				$stmtinsert->execute();
			}
			//Delete the shopping cart
			$stmtDelete = $mysqli->prepare("DELETE FROM d0018e_cart_details WHERE cart = ?");
			$stmtDelete->bind_param('s',$_SESSION['cart_id']);
			$stmtDelete->execute();
			echo 'Order completed';
		}	
	}else{//oops
		echo 'Something went wrong';
	}


}
include("store_html/bottom.html");

?>
<?php
	include("db.php");
    include("store_html/top.html");
    include("store_html/middle.html");
    $stmt = $mysqli->prepare("SELECT * FROM d0018e_orders");
    echo $mysqli->error;
    $stmt->execute();
    $result = $stmt->get_result();
    echo "<table>";
    echo "<thead><tr><th>User</th><th>Order_id</th><th>status</th></tr></thead>";
    echo "<tbody><tr>";
    while($row = $result->fetch_assoc()){
    	echo "<tr><td>".$row['user']. "</td><td>".$row['id']."</td><td>".$row['status']."</td><td><form action='admin_orders.php' method='post'><input hidden='id' name='id' value=".$row['id']."><button name='approve'>approve</button></form></td>";
    }
    $stmt->close();
    echo "</tr>";
    echo "</tbody></table>";
    //select_function($users, $mysqli);
    if(isset($_POST['approve'])){
    	$id=$_POST['id'];
    	$stmt = $mysqli->prepare("UPDATE d0018e_orders SET status = 1 WHERE id = ?");
    	$stmt->bind_param('s',$id);
    	$stmt->execute();
    }
    include("store_html/bottom.html");
?>
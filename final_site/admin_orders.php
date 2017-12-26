<?php
	include("db.php");
    include("store_html/top.html");
    include("store_html/menu.html");
    include("store_html/middle.html");
    $stmt = $mysqli->prepare("SELECT * FROM d0018e_orders");
    echo $mysqli->error;
    $stmt->execute();
    $result = $stmt->get_result();
    echo "<table>";
    echo "<thead><tr><th>User</th><th>Order_id</th><th>status</th></tr></thead>";
    echo "<tbody><tr>";
    while($row = $result->fetch_assoc()){
    	echo "<tr><td>".$row['user']. "</td><td>".$row['id']."</td><td>".$row['status']."</td><td><form action='admin_orders.php' method='post'><input hidden='id' name='id' value=".$row['id']."><button name='approve' value=".$row['status'].">approve</button></form></td>";
    }
    $stmt->close();
    echo "</tr>";
    echo "</tbody></table>";
    //Check if order has been approved already
    if(isset($_POST['approve'])=='0'){
    	$id=$_POST['id'];
        $truebol = FALSE;
        $stmt = $mysqli->prepare("SELECT quantity, prod_id from d0018e_order_details where order_id = ?");
        $stmt->bind_param('s',$id);
        $stmt->execute();
        $result=$stmt->get_result();
        $stmtcompare =  $mysqli->prepare("SELECT stock from d0018e_products where id=?");
        //This function checks if there is more of the product in stock than what the user wants to buy
        while($row = $result->fetch_assoc()){
            $stmtcompare->bind_param('s',$row['prod_id']);
            $stmtcompare->execute();
            $stmtcompare->store_result();
            $stmtcompare->bind_result($stock);
            $stmtcompare->fetch();
            if($row['quantity'] <= $stock){
                $truebol=TRUE;
            }else{
                $truebol=FALSE;
                break;
            }
            $stmtcompare->close();
        }//If there is enough in stock, this function will run
        if($truebol){
            $result->data_seek(0);
            $stmtinsert = $mysqli->prepare("UPDATE d0018e_products SET stock = stock - ? where id = ?");
            //Updates the stock of a product
            while($rows = $result->fetch_assoc()){
                $stmtinsert->bind_param('ss',$rows['quantity'],$rows['prod_id']);
                $stmtinsert->execute();
            }//changes the status of an order
            $stmt1 = $mysqli->prepare("UPDATE d0018e_orders SET status = 1 WHERE id = ?");
            $stmt1->bind_param('s',$id);
            $stmt1->execute();
        }else{
            echo 'Product out of stock'
        }

    }
    include("store_html/bottom.html");
?>
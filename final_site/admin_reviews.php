<?php

    include("db.php");
    
    function list_reviews($table, $mysqli) {
        echo '<table>
        <tr>
            <th>ID</th>
            <th>Rating</th>
            <th>Review</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>';
        $stmt = $mysqli->prepare("SELECT id, rating, review FROM $table");
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
        
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                echo '
            <form action="admin_reviews.php" method="post">
                <tr>
                    <input type="hidden" name="id" value="' . $row['id'] . '">
                    <th>' . $row['id'] . '</th>
                    <th>' . $row['rating'] . '</th>
                    <th>' . $row['review'] . '</th>
                    <th></th>
                    <th style="text-align: left;"><input type="submit" name="edit" value="Edit review"></th>
                    <th style="text-align: left;"><input type="submit" name="remove" value="Remove review"></th>
                </tr>
            </form>';
            }
            echo '</table>';
        }
        $stmt->close();
    }
    
    function edit_review($table, $mysqli) {
        
        $id = $_POST['id'];
        
        $stmt = $mysqli->prepare("SELECT id, rating, review FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
        echo '                <form action="admin_categories.php" method="post">
                    <input type="hidden" name="id" value="' . $row['id'] . '">
                    Rating: <input type="text" name="rating" value="' . $row['rating'] . '"><br>
                    Review: <textarea name="review">' . $row['review'] . '</textarea><br>
                    <input type="submit" name="edit_finish" value="Save review">
                    </form>';
        }
        $stmt->close();
    }
    
    function edit_review_finish($table, $mysqli) {
        $id = $_POST['id'];
        $rating = $_POST['rating'];
        $review = $_POST['review'];
        
        $stmt = $mysqli->prepare("UPDATE $table SET rating = ?, review = ? WHERE id = ?");
        $stmt->bind_param("isi", $rating, $review, $id);
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            echo 'Successfully edited item.';
        }
        $stmt->close();
    }
    
    function remove_review($table, $mysqli) {
        echo'                 Are you sure you want to delete selected item?
                    <form action="admin_categories.php" method="post">
                    <input hidden="id" name="id" value="' . $_POST['id'] . '">
                    <input type="submit" name="remove_finish" value="Remove">
                    </form>';
    }
    
    function remove_review_finish($table, $mysqli) {
        $id = $_POST['id'];
        
        $stmt = $mysqli->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        if(!$stmt) {
            echo 'Something went wrong: ' . $mysqli->error;
        } else {
            echo 'Successfully removed item.';
        }
        $stmt->close();
    }
    
    function select_function($table, $mysqli) {
        $edit = isset($_POST['edit']);
        $remove = isset($_POST['remove']);
        $add_finish = isset($_POST['add_finish']);
        $edit_finish = isset($_POST['edit_finish']);
        $remove_finish = isset($_POST['remove_finish']);
        
        if($edit) {
            edit_review($table, $mysqli);
        } else if($remove) {
            remove_review($table, $mysqli);
        } else if($edit_finish) {
            edit_review_finish($table, $mysqli);
        } else if($remove_finish){
            remove_review_finish($table, $mysqli);
        } else {
            list_reviews($table, $mysqli);
        }
    }
    
    include("store_html/top.html");
    include("store_html/menu.html");
    include("store_html/middle.html");
    select_function($reviews, $mysqli);
    include("store_html/bottom.html");
    
?>
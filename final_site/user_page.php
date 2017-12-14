<?php

    include("db.php");

    include("store_html/top.html");
    include("store_html/menu.html");
    include("store_html/middle.html");
    
    $id = $_SESSION['user_id'];
    
    $stmt = $mysqli->prepare("SELECT email, fName, sName, address, city, postcode, state, country FROM $users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($email, $fName, $sName, $address, $city, $postcode, $state, $country);
	$stmt->fetch();
    
    echo '
<table>
    <tr>
        <th colspan="2">Your personal information:</th>
    </tr>
    <tr>
        <th style="text-align: left;">Name:</th>
        <th style="text-align: left;">' . $fName . " " . $sName . '</th>
    </tr>
    <tr>
        <th style="text-align: left;">Email:</th>
        <th style="text-align: left;">' . $email . '</th>
    </tr>
    <tr>
        <th style="text-align: left;">Address:</th>
        <th style="text-align: left;">' . $address . '<br>
            ' . $postcode . ' ' . $city . '<br>
            ' . $state . ' ' . $country . '
        </th>
    </tr>
    <tr>
        <th style="text-align: left;">Phone:</th>
        <th style="text-align: left;">' . $phone . '</th>
    </tr>
    <tr>
        <th colspan="2" style="text-align: center;"><a href="">Edit information</a></th>
    </tr>
</table>';

    include("store_html/bottom.html");
?>
<?php

    include("db.php");

    include("store_html/top.html");
    include("store_html/menu.html");
    include("store_html/middle.html");
    echo '            <div class="contactbox">
            <form>
                <div class="contactbox_text">Your name</div>
                <div class="contactbox_input"><input type="text" name="name"></div>
                <div class="contactbox_text">Your email</div>
                <div class="contactbox_input"><input type="email" name="email"></div>
                <div class="contactbox_text">Message</div>
                <div class="contactbox_input"><textarea name="message" rows="10" cols="30"></textarea></div>
	           	<input type="button" value="Send">
            </form>
            </div>';
    include("store_html/bottom.html");

?>
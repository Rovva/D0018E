<?php
    $category = "D0018E_Categories";
    $orders = "D0018E_Orders";
    $products = "D0018E_Products";
    $carts = "D0018E_Carts";

    $dbhost = "lowert.se.mysql";
    $dbname = "lowert_se";
    $dbusr = "lowert_se";
    $dbpass = "fuckinghell123";
    global $conn;
    $conn = mysql_connect($dbhost, $dbusr, $dbpass);
    mysql_select_db($dbname);
    mysql_set_charset('utf8', $conn);
    if (!$conn) { 
        die('Could not establish a connection: ' . mysql_error());
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="D0018E">

        <title>D0018E - Webshop</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/shop-homepage.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="css/jquery-ui.css" rel="stylesheet">

        <script src="js/jquery.js"></script>
        <script src="js/jquery-ui.js"></script>
        <script>
            $( function() {
                $("#dialog").dialog({
                    autoOpen: false,
                    show: {
                        effect: "blind",
                        duration: 500
                    },
                    hide: {
                        effect: "explode",
                        duration: 500
                    }
                });

                $("#opener").on("click", function() {
                    $("#dialog").dialog("open");
                });
            });
        </script> 

    </head>

        <div id="dialog" title="Om oss" style="display: none;">
            <p>Hej och välkommen till shop.lowert.se!</p>
            <p>Denna hemsidan är ett projekt för kurs D0018E vid LTU.</p>
            <p>Skapad utav,<br>Simon Herbertsson och Adam Lowert.</p> 
        </div>

    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
		        <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">D0018E - Webshop</a>
                </div>
            
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li> <a id="opener" href="javascript:void(0);">Om oss</a> </li>
                        <li> <a href="information.php#services">Tjänster</a> </li>
                        <li> <a href="information.php#contact">Kontakta oss</a> </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="col-md-2-r">
                <p class="lead">Din kundkorg <b>(0)</b></p>
                <div class="list-group">
                    <a href="#" class="list-group-item">Testing this</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <p class="lead">Produkter</p>
                    <div class="list-group">
                        <?php //php-query
    
                            $qry = 'SELECT * FROM '.$category;
                            $out = mysql_query($qry, $conn);
                            if(!$out) { die('Could not fetch any data: ' . mysql_error()); }

                            while ($row = mysql_fetch_array($out)) {
                                echo '<a href="browse.php?cat='.$row['id'].'" class="list-group-item">'.$row['name'].'<b style="float:right;">('.$row['numProducts'].')</b></a>';
                            }
                        ?>
                    </div>
                </div>

                <div class="col-md-9">
                pew?
                </div>

                <div class="row">

                </div>


    </div>

    <div class="container">

        <hr>

        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Disclaimer; shop.lowert.se is made as a school project. Pictures and content is owned by <a href="https://www.komplett.se" target="_blank">Komplett.se</a>.</p>
                </div>
            </div>
        </footer>

    </div>

    <script src="js/jquery.js" />
    <script src="js/bootstrap.min.js" />

</body>
</html>

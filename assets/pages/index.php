<?php
    require_once "../includes/LIB-project1.php";


    $dbh = openDBH();

    if($dbh === null){
        echo "is null";
    }
    //null = no custom css
    $css = array("main.css", "bootstrap.min.css");

    $curr_page = "Home"; //even though the page is Admin, still considered "Account"


    //create the $pageHTML to print out
    $pageHTML = "<div class='container'><h1>Welcome to MicroController Center! </h1> <div class='jumbotron'><h2>Sale!</h2><div id='forSale'>";

    //get sale items, 1== true
    $sale = getInventory($dbh, 1);

    $pageHTML .= "</div></div><div class='jumbotron'><h2>MicroControllers</h2><div id='inventory'>";

    //get items not on sale, 0 == false
    //$notSale = getInventory($dbh, 0);

    $pageHTML .= "</div></div></div>";

    //include the template
    include "../includes/HTML_template.php";

    //close everything
    $dbh = null;
?>
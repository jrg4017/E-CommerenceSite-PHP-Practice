<?php

    //null = no custom css
    $css = array("main.css", "bootstrap.min.css");

    $curr_page = "Home"; //even though the page is Admin, still considered "Account"


    //create the $pageHTML to print out
    $pageHTML = "<div class='container'><h1>Welcome to MicroController Center! </h1> <div class='jumbotron'><h2>Sale!</h2><div id='forSale'>";

    //TODO grab the database information for stuff on sale
    //TODO plug it in using a loop

    $pageHTML .= "</div></div><div class='jumbotron'><h2>MicroControllers</h2><div id='inventory'>";

    //TODO grab the database information for stuff NOT on sale
    //TODO plug it in using a loop

    $pageHTML .= "</div></div></div>";

    //include the template
    include "../includes/HTML_template.php";

?>
<?php

    //null = no custom css
    $css = array("main.css", "bootstrap.min.css");

    $curr_page = "My Account"; //even though the page is Admin, still considered "Account"


    //create the $pageHTML to print out
    $pageHTML = "<h1>Welcome to Our Shop! </h1> <h2>Sale!</h2>";

    $pageHTML .= "<div id='forSale'>";

    //TODO grab the database information for stuff on sale
    //TODO plug it in using a loop

    $pageHTML .= "</div><h2></h2><div id='inventory'>";

    //TODO grab the database information for stuff NOT on sale
    //TODO plug it in using a loop

    $pageHTML .= "</div>";

    //include the template
    include "../includes/HTML_template.php";

?>
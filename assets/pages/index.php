<?php

    //null = no custom css
    $css = array("main.css", "pure-css.css");

    $curr_page = "My Account"; //even though the page is Admin, still considered "Account"


    //create the $pageHTML to print out
    $pageHTML = "<h1>Welcome to Our Shop! </h1> <h2>On Sale items</h2>";

    $pageHTML .= "<div id='forSale'>";

    //TODO grab the database information for stuff on sale
    //TODO plug it in using a loop

    $pageHTML .= "</div><div id='inventory'>";

    //TODO grab the database information for stuff NOT on sale
    //TODO plug it in using a loop

    $pageHTML .= "</div>";

    //include the template
    include "../includes/HTML_template.php";

?>
<?php
    require_once "../includes/LIB-project1.php";
    require_once "../includes/Paginator.class.php";

    $css = array("main.css", "bootstrap.min.css");
    $curr_page = "Cart";
    //set up the framework for the html header


    $dbh = openDBH();

    //if set, clear the cart database table and unset variable for future use
    if(isset($_GET['clearCart'])){
        clearCart($dbh);
        unset($_GET['clearCart']); //unset it so it'll pick up future ones
    }

    $pageHTML = "<div class='container'>"; //TODO include in container, custom end div, new div for index only, rest are 1 div standard in template
    $pageHTML .= displayCart($dbh);
    $pageHTML .= "</div>";

    include "../includes/HTML_template.php";

    //close connection
    $dbh = null;
?>
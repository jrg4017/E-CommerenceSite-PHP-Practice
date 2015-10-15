<?php
    require_once "../includes/LIB-project1.php";
    require_once "../includes/Paginator.class.php";
    require_once "../includes/Database.class.php";

    $css = array("main.css", "bootstrap.min.css");
    $curr_page = "Cart";
    //set up the framework for the html header

    $dbObj = new Database();

    //if set, clear the cart database table and unset variable for future use
    if(isset($_GET['clearCart'])){
        clearCart($dbObj);
        unset($_GET['clearCart']); //unset it so it'll pick up future ones
    }

    //save page HTML
    $pageHTML = "<div class='container'>";
    $pageHTML .= displayCart($dbObj);
    $pageHTML .= "</div>";

    include "../includes/HTML_template.php";

    //close connection
    $dbObj->closeDbh();
?>
<?php
    require_once "../includes/LIB-project1.php";
    require_once "../includes/Paginator.class.php";
    require_once "../includes/Database.class.php";

    //open connection
    $dbObj = new Database();

    //if set, add to cart and unset variable for future use
    if(isset($_GET['addToCart'])){
        addToCart($dbObj, $_GET['addToCart']);
        unset($_GET['addToCart']); //unset it so it'll pick up future ones
    }

    //null = css files
    $css = array("main.css", "bootstrap.min.css");

    $curr_page = "Home"; //even though the page is Admin, still considered "Account"

    //git divs for sales / category
    getSalesCatalog($dbObj, $curr_page);

    //include the template
    include "../includes/HTML_template.php";

    //close everything
    $dbObj->closeDbh();
?>
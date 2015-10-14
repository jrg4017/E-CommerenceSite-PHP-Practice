<?php
    require_once "../includes/LIB-project1.php";
    require_once "../includes/Paginator.class.php";

    //open connection
    $dbh = openDBH();

    //if set, add to cart and unset variable for future use
    if(isset($_GET['addToCart'])){
        addToCart($dbh, $_GET['addToCart']);
        unset($_GET['addToCart']); //unset it so it'll pick up future ones
    }

    //null = css files
    $css = array("main.css", "bootstrap.min.css");

    $curr_page = "Home"; //even though the page is Admin, still considered "Account"


    //create the $pageHTML to print out
    $pageHTML = "<div class='container'><h1>Welcome to the MicroController Center! </h1> <div class='jumbotron'><h2>Items on SALE</h2><div id='forSale'>";

    //get sale items, 1== true
    $sale = getInventory($dbh, 1);
    //print out items into the correct div
    $saleDiv = new Paginator($sale);
    $pageHTML .= $saleDiv->displayPagination(1, true, false);

    $pageHTML .= "</div></div><div class='jumbotron'><h2>MicroControllers Catalog</h2>";

    //get items not on sale, 0 == false
    $notSale = getInventory($dbh, 0);
    $itemsDiv = new Paginator($notSale);

    //default if not exists
    if(!isset($_GET['page'])){ $_GET['page'] = 1; }

    $pageHTML .= $itemsDiv->displayPagination($_GET["page"], false, false);

    $pageHTML .= "</div></div>";

    //include the template
    include "../includes/HTML_template.php";

    //close everything
    $dbh = null;
?>
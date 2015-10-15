<?php
    require_once "../includes/LIB-project1.php";
    require_once "../includes/Database.class.php";

    $dbObj = new Database();

    //null = no custom css
    $css = array("main.css", "bootstrap.min.css");
    $curr_page = "My Account"; //even though the page is Admin, still considered "Account"

        //if editItem is set, pass the id to get the filled out form with items
        //if submit is set, work on validation and then updating if set
        //else display table to select item to edit
         if(isset($_POST['editItem'])){
             $pageHTML = editItem($_POST['editItem'], $dbObj);
             unset($_POST['editItem']);
             unset($_POST['submit']);
         } else if(isset($_POST['submit'])) {
             $pageHTML = validate($dbObj);
             unset($_POST['submit']);
             $pageHTML .= getTable($dbObj);
         }else{ //else print out table of database items
             $pageHTML = getTable($dbObj);
         }

    //set up the framework for the html header
    include "../includes/HTML_template.php";

    $dbObj->closeDbh();

?>
<?php
    require_once "../includes/LIB-project1.php";
    require_once "../includes/Database.class.php";

    //null = no custom css
    $css = array("main.css", "bootstrap.min.css");
    $curr_page = "My Account"; //even though the page is Admin, still considered "Account"

         if(isset($_POST['editItem'])){
             //getForm($_POST['editItem']);
             
             unset($_POST['editItem']);
         } else{
//             $pageHTML = "<div class='container'><div class='jumbotron'><form method='POST' action='admin.php' >
//             <h2>What would you like to do?</h2>
//             <br><input type='radio' name='function' value='addSales'>Add Sales Item
//             <br><input type='radio' name='function' value='editCatalog'>Edit Catalog Item
//             <br><input type='radio' name='function' value='removeSales'>Remove Sales Item
//             <br><br><button class='btn btn-primary'  type='submit' >Submit</button>
//             </form></div></div>";
             getTable($dbObj);
         }


    //set up the framework for the html header
    include "../includes/HTML_template.php";



?>
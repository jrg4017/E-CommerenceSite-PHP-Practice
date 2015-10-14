<?php

    $navArry = array(
        "Home" => "index.php",
        "Cart" => "cart.php",
        "My Account" => "login.php"
    );

    //print out the navigation


    echo "<nav class='navbar navbar-default'><div class='container-fluid'>";
    //header information for website title (bootstrap)
    echo "<div class='navbar-header'><button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>
         <span class='sr-only'>Toggle navigation</span><span class='icon-bar'></span><span class='icon-bar'></span><span class='icon-bar'></span></button><a class='navbar-brand' id='navHeader' href='#'>MicroController Center</a></div>";
    echo "<div id='navbar' class='navbar-collapse collapse'><ul class='nav navbar-nav'>";

    foreach($navArry as $page => $href){
        $str = "<li><a href='";
        if($page === $curr_page){
            $str .= "#' id='currPage'";
        }else{
            $str .=  URL_PAGES .  $href . "'";
        }

        $str .= " class='pure-menu-link'>" . $page . "</a></li>";

        echo $str;

    }

    echo "</ul></div></div></nav>";


?>
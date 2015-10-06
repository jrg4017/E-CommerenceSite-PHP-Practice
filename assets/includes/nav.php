<?php

    $navArry = array(
        "Home" => "index.php",
        "Cart" => "cart.php",
        "My Account" => "login.php"
    );

    //print out the navigation
    echo "<nav class='pure-menu pure-menu-horizontal'><ul class='pure-menu-list'>";

    foreach($navArry as $page => $href){
        $str = "<li class='pure-menu-item'><a href='";
        if($page === $curr_page){
            $str .= "#' id='currPage'";
        }else{
            $str .=  URL_PAGES .  $href . "'";
        }

        $str .= " class='pure-menu-link'>" . $page . "</a></li>";

        echo $str;

    }

    echo "</ul></nav>";


?>
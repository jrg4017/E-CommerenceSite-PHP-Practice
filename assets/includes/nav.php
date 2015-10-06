<?php

    $navArry = array(
        "Home" => "index.php",
        "Cart" => "cart.php",
        "My Account" => "login.php"
    );

    //print out the navigation
    echo "<nav><ul>";

    foreach($navArry as $page => $href){
        $str = "<li><a href='";
        if($page === $curr_page){
            $str .= "#' id='currPage'"; //TODO currPage css in main? or nav.css?
        }else{
            $str .=  $href . "''"; //TODO add a URL holder here for the href (or in array holder??)
        }

        $str .= ">" . $page . "</a></li>";

        echo $str;

    }

    echo "</ul></nav>";

?>
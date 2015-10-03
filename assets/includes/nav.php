<?php

    $navArry = array(
        "Home" => "index.php",
        "Cart" => "cart.php",
        "Login" => "admin.php"
    );

    //print out the navigation
    echo "<nav><ul>";

    foreach($navArry as $page => $href){
        $str = "<li><a href='";
        if($page === $curr_page){
            $str .= "#' id='currpage'";
        }else{
            $str .= $href . "''";
        }

        $str .= ">" . $page . "</a></li>";

        echo $str;

    }

    echo "</ul></nav>";

?>
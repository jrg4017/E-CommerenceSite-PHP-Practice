<?php
    $css = array("main.css", "bootstrap.min.css");
    $curr_page = "My Account";
    require_once "../includes/LIB-project1.php";
    require_once "../includes/Database.class.php";

    $dbObj = new Database();

    if(isset($_POST['email']) && isset($_POST['password'])){
        $msg = ""; //empty declaration for now
        $rtn = validateLogIn($dbObj, $_POST['email'], $_POST['password']);

        //if sucessful, move to admin, if failed print message
        if($rtn == true){ header("Location: admin.php"); exit; }
        else{$msg = "Your username or password is incorrect. Please try again."; }
    }

//TODO add session functionality

    $pageHTML = "<div class='container'><div class='jumbotron'><form class='form-signin' action='login.php' method='POST'>
        <div>" . $msg . "</div>
        <h2 class='form-signin-heading'>Please sign in</h2>
        <label for='inputEmail' class='sr-only'>Email address</label>
        <input type='email' id='inputEmail' class='form-control' name='email' placeholder='Email address' required='' autofocus=''>
        <label for='inputPassword' class='sr-only'>Password</label>
        <input type='password' id='inputPassword' class='form-control'  name='password' placeholder='Password' required=''>
        <div class='checkbox'> <label> <input type='checkbox' value='remember-me'> Remember me</label></div>
        <button class='btn btn-lg btn-primary btn-block' type='submit'>Sign in</button></form></div></div>";

//TODO add submit button to go to appropiate admin account page

    //set up the framework for the html header
    include "../includes/HTML_template.php";
?>
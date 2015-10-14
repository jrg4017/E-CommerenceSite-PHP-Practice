<?php
    $css = array("main.css", "bootstrap.min.css");
    $curr_page = "My Account";

    $pageHTML = "<div class='container'><form class='form-signin'>
        <h2 class='form-signin-heading'>Please sign in</h2>
        <label for='inputEmail' class='sr-only'>Email address</label>
        <input type='email' id='inputEmail' class='form-control' placeholder='Email address' required='' autofocus=''>
        <label for='inputPassword' class='sr-only'>Password</label>
        <input type='password' id='inputPassword' class='form-control' placeholder='Password' required=''>
        <div class='checkbox'> <label> <input type='checkbox' value='remember-me'> Remember me</label></div>
        <button class='btn btn-lg btn-primary btn-block' type='submit'>Sign in</button></form> </div> ";

//TODO add submit button to go to appropiate admin account page

    //set up the framework for the html header
    include "../includes/HTML_template.php";
?>
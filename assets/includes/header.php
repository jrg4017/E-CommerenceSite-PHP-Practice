<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title><?php echo $curr_page; ?></title>
  <meta name="description" content="E-Commerence shop for all your shopping needs">
  <meta name="author" content="Julianna Gabler">


    <?php
        //prints out a line for each css page in the array
        foreach($css as $cssPage){
            echo "<link rel='stylesheet' href='../css/" . $cssPage . "'>";
        }
    ?>
</head>


<body>

<?php include "nav.php"; ?>
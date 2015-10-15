<!Doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title><?php echo "The MicroController Center:  " . $curr_page; ?></title>
  <meta name="description" content="E-Commerence shop for all your microcontroller needs">
  <meta name="author" content="Julianna Gabler">


    <?php
        //prints out a line for each css page in the array
        foreach($css as $cssPage){
            echo "<link rel='stylesheet' href='../css/" . $cssPage . "'>";
        }
    ?>
    <!-- needed scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</head>


<body>

<?php
    //include navigation
    include "nav.php";


    //print out the page's HTML
    echo $pageHTML;

?>

</body>
</html>
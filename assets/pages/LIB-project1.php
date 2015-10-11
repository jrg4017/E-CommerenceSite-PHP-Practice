<?php

/**
 * LIST OF FUNCTIONS REQUIRED FOR PROJECT - Normally would separate functions out to some degree, but
 */

/**********************************************************************************/
/******** Database ****************************************************/
/*********************************************************************************/

//the database information to connect are located elsewhere for security reasons
//include "../../../../db_info.php";

    $db = "jrg4017";
    $user = "jrg4017";
    $pass = "fr1end";
    $host = "localhost"; //TODO get host

    //function to open db connection
/**
 * opens a database connection object and returns it
 * if the database fails to open, returns null and program catches later
 * @return PDO
 */
function openDBH(){
    try {
        $dbh = new PDO("db=mysql;", $user, $pass); //TODO look up correct string usuage for dsn as it's changed
        return $dbh;
    }catch (PDOException $e){
        echo "<h2>Unexpected Error. Please try another time</h2>h2>";
        return null;
    }
    //return $dbh; // throw PDOException;
}//end openDBH

/**
 * gets the current sale items in the database and
 * returns an array of Inventory Item Objects
 * @param $dbh - the connection obj
 * @return array - the array of Inventory Item objects
 */
function getSaleItems($dbh){
    //prepare the array to save objects in
    $sale = array();

    //prepare the query and save as a result
    $stmnt = $dbh->prepare("SELECT name, price, image, description, quantity FROM Inventory WHERE onsale = true");
    $stmnt->execute();

    //grab result and load the neccessary information into the object and array
    while($result = $stmnt->fetch(PDO::FETCH_ASSOC)){
        //load
        $obj = new InventoryItem($result);

        $sale[] = $obj;
    }//close while

    return $sale;
}//end getSaleItems

/**********************************************************************************/
/******** SALE ITEM FUNCTIONS ****************************************************/
/*********************************************************************************/

/**
 * designed to get all items marked as "ON SALE" in the database
 * Validates that the parameters are met
 * returns an array of objects containing the item's information
 */
function getSales($dbh){
    //prepare the
    $results = $dbh->prepare("SELECT * FROM Inventory WHERE onsale = true");

    foreach($results as $saleItem){

    }

}
/**
 * validates that the number of items on sale
 *      no more than 5 items
 *      no less than 3 items
 * @param $count
 * @return bool
 */
function validateSales($count){
    //if the count is 5 or greater, item cannot be added as on sale
    //if count is 3 or less, item cannot be subtracted
    if($count <= 5 || $count <=3){
        return false;
    }
    //default if the if statement not called
    return true;
}//close validateSales
<?php
//include the InventoryItem class for use
include "InventoryItem.class.php";

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
function openDBH($host, $db, $pass, $user){
    try {
        $dbh = new PDO("mysql:host=$host;dbname=$db", $user, $pass); //TODO look up correct string usuage for dsn as it's changed
        return $dbh;
    }catch (PDOException $e){
        echo "<h2>Unexpected Error. Please try another time</h2>h2>";
        return null;
    }
    //return $dbh; // throw PDOException;
}//end openDBH
/**
 * updates the function based on query and params
 *      print out message based on success or failure
 * @param $dbh - the current db connection
 * @param $query - the query specifics to update
 * @param $param - the params to use, array s
 * @return string - the string msg to return
 */
function update($dbh, $query, $param){
    //query function
    try { //TODO see if item exists first
        $stmnt = $dbh->prepare($query);
        //$stmnt->bindValue($params); //TODO find out if okay to bind value to param without ":name"
        $stmnt->execute($param);

        $msg = "Update was successful!";

        return $msg;

    }catch(PDOException $e){
        //TODO print out message
    }
}//end update

function delete($dbh){

}

function add($dbh){

}

/**
 * gets the number of current
 * @param $dbh - the current db connection
 * @return $count - the number rows in sales
 */
function getSalesCount($dbh){
    //prepare query and
    $query = "SELECT name FROM Inventory WHERE onsale = true";

    //prepare and run
    $stmt = $dbh->prepare($query);
    $stmt->execute();

    //return the number of rows already a sales item
    return $stmt->rowCount();
}//end getSalesCount

/**********************************************************************************/
/******** SALE ITEM FUNCTIONS ****************************************************/
/*********************************************************************************/

/**
 * designed to get all items marked as "ON SALE" in the database
 * returns an array of objects containing the item's information
 */
function displaySales($dbh){
    //get sales items and
    $salesItems = getSaleItems($dbh);

}
/**
 * validates that the number of items on sale
 *      no more than 5 items
 *      no less than 3 items
 * @param $dbh - the current db connection
 * @return bool
 */
function validateSales($dbh){
    $count = getSalesCount($dbh);
    //if the count is 5 or greater, item cannot be added as on sale
    //if count is 3 or less, item cannot be subtracted
    if($count <= 5 || $count <=3){
        return false;
    }
    //default if the if statement not called
    return true;
}//close validateSales

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

/**
 * updates a current inventory item to onsale = true
 * validates parameters are met and then returns a message based on
 *      parameters not being met
 *      update failure
 *      update success
 * @param $dbh - the current db conn
 * @param $params - the item to update
 * @return string - the message to print out
 */
function addSalesItem($dbh, $params){
    $valid = validateSales($dbh);
    //empty string
    $string = "";

    //push out failed message
    if ($valid == false){
        $count = getSalesCount($dbh);
        $string = "You have " . $count . " items on sale. <br> You must have at least 3 items on sale. <br> You cannot have more than 5 items";
        return $string;
    }

    $query = "UPDATE INVENTORY SET onsale = true WHER name= :name";

    //update the item to the sales inventory or print the returned message
    $string = update($dbh, $query, $params);

    return $string;
}//end addSalesItem



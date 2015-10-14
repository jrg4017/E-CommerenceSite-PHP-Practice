<?php

//include the InventoryItem class for use
include "InventoryItem.class.php";

/**
 * LIST OF FUNCTIONS REQUIRED FOR PROJECT - Normally would separate functions out to some degree, but
 */

/**********************************************************************************/
/******** Database ****************************************************/
/*********************************************************************************/

/**
 * opens a database connection object and returns it
 * if the database fails to open, returns null and program catches later
 * @return PDO
 */
function openDBH(){

    require_once "../../../../db_info.php";

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
function updateDelete($dbh, $query, $param){
    //query function
    try { //TODO see if item exists first
        $stmnt = $dbh->prepare($query);
        $stmnt->execute($param);

        $count = $stmnt->rowCount();

        $msg = "$count entries were updated.";

        return $msg;

    }catch(PDOException $e){
        //TODO look into duplicate testing to catch error
        $msg = "Error with operation, Please try again later";
        return $msg;
    }
}//end updateDelete


/**
 * gets the number of current
 * @param $dbh - the current db connection
 * @return $count - the number rows in sales
 */
function getSalesCount($dbh){
    //prepare query and
    $query = "SELECT name FROM ITEMSALE WHERE onsale = true";

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
 * validates that the number of items on sale
 *      no more than 5 items
 *      no less than 3 items
 * @param $dbh - the current db connection
 * @return mixed;
 */
function validateSales($dbh){
    $count = getSalesCount($dbh);
    //if the count is 5 or greater, item cannot be added as on sale
    //if count is 3 or less, item cannot be subtracted
    if($count <= 5 || $count <=3){
        return "You have " . $count . " items on sale. <br> You must have at least 3 items on sale. <br> You cannot have more than 5 items";
    }
    //default if the if statement not called
    return true;
}//close validateSales

///**
// * gets the current sale items in the database and
// * returns an array of Inventory Item Objects
// * @param $dbh - the connection obj
// * @return array - the array of Inventory Item objects
// */
//function getSaleItems($dbh){
//    //prepare the array to save objects in
//    $sale = array();
//
//    //prepare the query and save as a result
//    $stmnt = $dbh->prepare("SELECT t1.name,  t1.image, t2.price, t1.description, t2.quantity, t3.onsale, t3.salePrice FROM ITEM t1 JOIN INVENTORY t2 ON t1.itemId = t2.itemId JOIN ITEMSALE t3 ON t2.itemId = t3.itemId WHERE t3.onsale = 1");
//    $stmnt->execute();
//
//    //grab result and load the neccessary information into the object and array
//    while($result = $stmnt->fetch(PDO::FETCH_ASSOC)){
//        //load
//        $obj = new InventoryItem($result);
//
//        $sale[] = $obj;
//    }//close while
//
//    return $sale;
//}//end getSaleItems

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

    //push out failed message
    if ($valid !== true){ return $valid; }

    //preload query
    $query = "UPDATE ITEMSALE SET onsale = true WHERE name = :name";

    //update the item to the sales inventory or print the returned message
    $msg = updateDelete($dbh, $query, $params);

    return $msg;
}//end addSalesItem

/**
 * removes the item from the sales category but not from the database
 *      sales items parameters has to be met in order for removal
 *          no less than 3 items and no more than 5
 * @param $dbh
 * @param $paran
 * @return mixed|string
 */
function removeSalesItem($dbh, $param){
    $valid = validateSales($dbh);

    if($valid !== true){ return $valid;}

    //preload query
    $query = "UPDATE INVENTORY SET onsale = false WHERE name = :name";

    $msg = updateDelete($dbh, $query, $param);

    return $msg;
}//end removeSalesItem

//TODO display sales function or on page??

/**********************************************************************************/
/******** REGULAR ITEM FUNCTIONS **************************************************/
/**********************************************************************************/
/**
 * gets the inventory items requested, based on desired sale or not on sale items
 * @param $dbh - the current db connection
 * @param $isSale - whether on sale items are desire or not (bool)
 * @return array - the array of InventoryItem objects
 */
function getInventory($dbh, $isSale){

    //get all items not on sale and return
    $stmnt = $dbh->prepare("SELECT t1.itemid, t1.name,  t1.image, t2.price, t1.description, t2.quantity, t3.onsale, t3.salePrice FROM item t1 JOIN inventory t2 ON t1.itemId = t2.itemId JOIN itemsale t3 ON t2.itemId = t3.itemId WHERE t3.onsale = :onsale");
    $stmnt->bindParam(":onsale", $isSale);
    $stmnt->execute();

    //empty array
    $array = array();

    $result = $stmnt->fetchAll();

    foreach($result as $row){

        $obj = new InventoryItem($row);

        $array[] = $obj;
    }

    return $array;
}//end getInventory

/**
 * adds or updates item in cart
 * @param $dbh
 * @param $obj
 */
function addToCart($dbh, $obj){
    //check to see item exists to update count
    $num = checkExists($dbh);
    //if item doesn't exist, insert into table
    if($num ==0 ){ $query = "INSERT INTO CART (id, quantity) VALUES(:id, 1)"; }
    //does exist, update table to quantity
    else{ $query = "UPDATE CART SET quantity = :quantity WHERE id = :id";
            $quantity = $num + 1;
    }

    $stmnt = $dbh->prepare($query);
    $stmnt->bindParam(":id", $obj->getId());

    //if num was greater than 0, bind quantity param
    if($num > 0){
        $stmnt->bindParam(":quantity", $quantity);
    }

    $stmnt->execute();
}//end addToCart
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
function updateDeleteInsert($dbh, $query, $param){
    //query function
    try { //TODO see if item exists first
        $stmnt = $dbh->prepare($query);
        $stmnt->execute($param);

        $count = $stmnt->rowCount();

        $msg = "$count entries were updated.";

        return $msg;

    }catch(PDOException $e){
        $msg = "Error with operation, Please try again later";
        return $msg;
    }
}//end updateDeleteInsert



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

/**
 * check to see if variable exists and return true if does (greater than 0 returned)
 * return false if 0
 * @param $dbh
 * @param $query
 * @param $params
 * @return mixed - return the number of rows
 */
function checkExists($dbh, $query, $params){
    try{
        $stmnt = $dbh->prepare($query);

        foreach($params as $key => $val){
            $stmnt->bindParam($key, $val);
        }

        $stmnt->execute();

        $count = $stmnt->rowCount();

        if($count > 0){
            //get the variable requested and return it, else return 0
            $rs = $stmnt->fetchAll();
            $result = $rs[0]["quantity"];
            return $result;
        }
        else{ return 0; }
    }catch(PDOException $e){
        return 0;
    }
}//end checkExists

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
    $query = "UPDATE itemsale SET onsale = true WHERE name = :name";

    //update the item to the sales inventory or print the returned message
    $msg = updateDeleteInsert($dbh, $query, $params);

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

    $msg = updateDeleteInsert($dbh, $query, $param);

    return $msg;
}//end removeSalesItem


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
    $stmnt = $dbh->prepare("SELECT t1.itemid, t1.name,  t1.image, t2.price, t1.description,
                            t2.quantity, t3.onsale, t3.salePrice FROM item t1 JOIN inventory
                            t2 ON t1.itemId = t2.itemId JOIN itemsale t3 ON t2.itemId = t3.itemId
                            WHERE t3.onsale = :onsale");
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
/**********************************************************************************/
/******** CART FUNCTIONS **********************************************************/
/**********************************************************************************/
/**
 * adds or updates item in cart
 * @param $dbh
 * @param $id
 */
function addToCart($dbh, $id){

    $param = array(":id" => $id);
    //check to see item exists to update count
    $num = checkExists($dbh, "SELECT quantity FROM cart WHERE itemId = :id", $param );
    //if item doesn't exist, insert into table

    $quantity = $num + 1;
    $params= array(":id" => $id, ":quantity"=> $quantity);
    if($num == 0 ){ $query = "insert into cart (itemid, quantity) values (:id,:quantity)"; }
    //does exist, update table to quantity using updateDeleteInsert function and array of params
    else{ $query = "UPDATE cart SET quantity = :quantity WHERE itemId = :id"; }

    updateDeleteInsert($dbh, $query, $params);

    //no matter what subtract one from item quantity
    $query = "UPDATE inventory SET quantity = :quantity WHERE itemid = :id";
    $quantity = (checkExists($dbh, "SELECT quantity FROM inventory WHERE itemid = :id", $param)) -1 ;
    $params = array(":id"=> $id, ":quantity"=>$quantity);
    updateDeleteInsert($dbh, $query, $params);
}//end addToCart

/**
 * displays current cart contents
 * @param $dbh
 * @return string
 */
function displayCart($dbh){
    $stmt = $dbh->prepare("SELECT t1.itemid, t1.name,  t1.image, t2.price, t1.description,
                            t4.quantity, t3.onsale, t3.salePrice FROM cart t4
                            JOIN item t1 ON t4.itemId = t1.itemId
                            JOIN inventory t2 ON t1.itemId = t2.itemId
                            JOIN itemsale t3 ON t2.itemId = t3.itemId");

    $stmt->execute();
    $rs = $stmt->fetchAll();
    //empty cart as array
    $cart = array();
    foreach($rs as $result){
        $obj = new InventoryItem($result);
        $cart[] = $obj;
    }

    $cartDiv = new Paginator($cart);

    if(!isset($_GET['page'])){ $_GET['page'] = 1; }
    $pageHTML .= $cartDiv->displayPagination($_GET['page'], false, true);

    //TODO display cart TOTAL price

    return $pageHTML;

}//end displayCart

function clearCart($dbh){
    $arr = array();
   updateDeleteInsert($dbh, "DELETE FROM cart", $arr);
}//end clear cart



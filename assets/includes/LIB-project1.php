<?php

//include the InventoryItem class for use
include "InventoryItem.class.php";

/**********************************************************************************/
/******** REGULAR ITEM FUNCTIONS **************************************************/
/**********************************************************************************/
/**
 * gets the inventory items requested, based on desired sale or not on sale items
 * @param $dbObj
 * @param $isSale - whether on sale items are desire or not (bool)
 * @return array - the array of InventoryItem objects
 */
function getInventory($dbObj, $isSale){

    //get all items not on sale and return
    $stmnt = $dbObj->getDbh()->prepare("SELECT t1.itemid, t1.name,  t1.image, t2.price, t1.description,
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

function getSalesCatalog($dbObj, $currpage){
    //create the $pageHTML to print out
    $pageHTML = "<div class='container'><h1>Welcome to the MicroController Center! </h1> <div class='jumbotron'><h2>Items on SALE</h2>";

    //get sale items, 1== true
    $sale = getInventory($dbObj, 1);
    //print out items into the correct div
    $saleDiv = new Paginator($sale);
    $pageHTML .= $saleDiv->displayPagination(1, true, false);

    $pageHTML .= "</div></div><div class='jumbotron'><h2>MicroControllers Catalog</h2><div id='inventory'>";

    //get items not on sale, 0 == false
    $notSale = getInventory($dbObj, 0);
    $itemsDiv = new Paginator($notSale);

    //default if $_GET var doesn't exist: page = 1
    if(!isset($_GET['page'])){ $_GET['page'] = 1; }
    $pageHTML .= $itemsDiv->displayPagination($_GET["page"], false, false);

    $pageHTML .= "</div></div></div>";

    return $pageHTML;
}
/**********************************************************************************/
/******** CART FUNCTIONS **********************************************************/
/**********************************************************************************/
/**
 * adds or updates item in cart
 * @param $dbObj
 * @param $id
 */
function addToCart($dbObj, $id){

    $param = array(":id" => $id);
    //check to see item exists to update count
    $num = $dbObj->checkExists("SELECT quantity FROM cart WHERE itemId = :id", $param );
    //if item doesn't exist, insert into table

    $quantity = $num + 1;
    $params= array(":id" => $id, ":quantity"=> $quantity);
    if($num == 0 ){ $query = "insert into cart (itemid, quantity) values (:id,:quantity)"; }
    //does exist, update table to quantity using updateDeleteInsert function and array of params
    else{ $query = "UPDATE cart SET quantity = :quantity WHERE itemId = :id"; }

    $dbObj->updateDeleteInsert($query, $params);

    //no matter what subtract one from item quantity
    $query = "UPDATE inventory SET quantity = :quantity WHERE itemid = :id";
    $quantity = ($dbObj->checkExists("SELECT quantity FROM inventory WHERE itemid = :id", $param)) -1 ;
    $params = array(":id"=> $id, ":quantity"=>$quantity);
    $dbObj->updateDeleteInsert($query, $params);
}//end addToCart

/**
 * displays current cart contents
 * @param $dbObj
 * @return string
 */
function displayCart($dbObj){
    $stmt = $dbObj->getDbh()->prepare("SELECT t1.itemid, t1.name,  t1.image, t2.price, t1.description,
                            t2.quantity, t3.onsale, t3.salePrice FROM item t1 JOIN inventory
                            t2 ON t1.itemId = t2.itemId JOIN itemsale t3 ON t2.itemId = t3.itemId");

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
    $pageHTML = $cartDiv->displayPagination($_GET['page'], false, true);

    return $pageHTML;

}//end displayCart
/**
 * clears the cart when the button is clicked
 * @param $dbObj
 */
function clearCart($dbObj){
    $arr = array();
    $dbObj->updateDeleteInsert("DELETE FROM cart", $arr);

}//end clear cart


/**********************************************************************************/
/******** PASSWORD FUNCTIONS ******************************************************/
/**********************************************************************************/
function validateLogIn($dbObj, $email, $pass){
    $query = "SELECT email FROM users WHERE email = :email AND password = Password(:password)";
    $stmnt = $dbObj->getDbh()->prepare($query);

    $stmnt->bindParam(":email", $email);
    $stmnt->bindParam(":password", $pass);

    $stmnt->execute();

    $count = $stmnt->rowCount();

    if($count > 0 ){
        return true;
    }else{
        return false;
    }
}//end validateLogIn

/**********************************************************************************/
/******** ADMIN FUNCTIONS ******************************************************/
/**********************************************************************************/
/**
 * gets table of objects and a button for editing
 * @param $dbObj
 * @return string
 */
function getTable($dbObj){
    $stmt =$dbObj->getDbh()->prepare("SELECT t1.itemid, t1.name,  t1.image, t2.price, t1.description,
                            t4.quantity, t3.onsale, t3.salePrice FROM cart t4
                            JOIN item t1 ON t4.itemId = t1.itemId
                            JOIN inventory t2 ON t1.itemId = t2.itemId
                            JOIN itemsale t3 ON t2.itemId = t3.itemId");

    $stmt->execute();
    $rs = $stmt->fetchAll();

    $html = "<form method='POST' action='admin.php'><table class='table table-striped'><thead>
        <th>Id</th><th>Name</th><th>Price</th><th>On Sale</th><th>Sale Price</th><th>Description</th><th>Quantity</th><th>Edit</th>
        </thead>";

    foreach($rs as $result){
        $obj = new InventoryItem($result);

        if($obj->getOnSale() == 1){ $os = "true";}else{ $os = "false";}

        $html .= "<tr><td>". $obj->getId() . "</td><td>" . $obj->getName() ."</td>
                  <td>". $obj->getPrice() . "</td><td>" . $os ."</td><td>" . $obj->getSalePrice() .
                  "</td><td>" . $obj->getDescription() . "</td><td>". $obj->getQuantity() ."</td>
                  <td><button class='btn btn-primary'  name='editItem=' value='" . $obj->getId() ."' >Edit Item</button></td></tr>";

    }

    $html .= "</table></form>";

    return $html;
}//end getTable

function getForm($formName){
    if($formName === 'addSales'){ addSalesItem(); }
    elseif($formName === 'editCatalog'){ editCatalogItem(); }
    else{ removeSalesItem(); }

}//end getForm


function addSalesItem(){

}//end addSalesItem

function editCatalogItem(){

}//end editCatalogItem

function removeSalesItem(){

}//end removeSalesItem

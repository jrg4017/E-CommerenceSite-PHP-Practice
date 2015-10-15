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
    $query ="SELECT t1.itemid, t1.name,  t1.image, t2.price, t1.description,
                            t2.quantity, t3.onsale, t3.salePrice FROM item t1 JOIN inventory
                            t2 ON t1.itemId = t2.itemId JOIN itemsale t3 ON t2.itemId = t3.itemId
                            WHERE t3.onsale = :onsale";
    $result = $dbObj->select($query, array(":onsale"=>$isSale));

    //empty array
    $array = array();

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
    $query = "SELECT t4.itemid, t1.name,  t1.image, t2.price, t1.description,
                            t4.quantity, t3.onsale, t3.salePrice FROM cart t4 JOIN item t1 ON t4.itemId = t1.itemId
                            JOIN inventory t2 ON t1.itemId = t2.itemId JOIN itemsale t3 ON t2.itemId = t3.itemId";

    $rs = $dbObj->select($query);

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
    $dbObj->updateDeleteInsert("DELETE FROM cart", array());

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
    $query = "SELECT t1.itemid, t1.name,  t1.image, t2.price, t1.description,
                            t2.quantity, t3.onsale, t3.salePrice FROM item t1
                            JOIN inventory t2 ON t1.itemId = t2.itemId
                            JOIN itemsale t3 ON t2.itemId = t3.itemId";

    $rs = $dbObj->select($query);

    $html = "<div class='container'><h2>Catalog Items</h2><div class='jumbotron'>
        <form method='POST' action='admin.php'><table class='table table-striped table-bordered'><thead>
        <th>Id</th><th>Name</th><th>Price</th><th>On Sale</th><th>Sale Price</th><th>Description</th><th>Quantity</th><th>Edit</th>
        </thead>";

    foreach($rs as $result){
        $obj = new InventoryItem($result);

        if($obj->getOnSale() == 1){ $os = "True";}else{ $os = "False";}

        $html .= "<tr><td>". $obj->getId() . "</td><td>" . $obj->getName() ."</td>
                  <td>". $obj->getPrice() . "</td><td>" . $os ."</td><td>" . $obj->getSalePrice() .
                  "</td><td>" . $obj->getDescription() . "</td><td>". $obj->getQuantity() ."</td>
                  <td><button class='btn btn-primary'  name='editItem' value='" . $obj->getId() ."' >Edit Item</button></td></tr>";

    }

    $html .= "</table></form></div></div>";

    return $html;
}//end getTable
/**
 * edit item form
 * @param $id
 * @param $dbObj
 * @return string
 */
function editItem($id, $dbObj){
    $query = "SELECT t1.itemId, t1.name,  t1.image, t2.price, t1.description,
                            t2.quantity, t3.onsale, t3.salePrice FROM item t1
                            JOIN inventory t2 ON t1.itemId = t2.itemId
                            JOIN itemsale t3 ON t2.itemId = t3.itemId WHERE t1.itemId = :itemid";
    $rs = $dbObj->select($query, array(":itemid"=>$id));

    $html = "<div class='container'><h2>Edit Item</h2><div class='jumbotron'><form method='POST' action='admin.php'>";
    //save into fields
    foreach($rs as $result){
        $obj = new InventoryItem($result);

        $html .= "<label name='id' value='" . $obj->getId() .  "'>ID:" . $obj->getId() . "</label><label for='name'>Name: </label>
                  <br><br><input type='text' name='name' id='name' value='" . $obj->getName() . "'>
                  <br><br><label for='image'>Image: </label><input type='text' name='image' id='image' value='" . $obj->getImage() . "'>
                  <br><br><label for='price'>Price: </label><input type='text' name='price' id='price' value='" . $obj->getPrice(). "'>
                  <br><br><label for='quantity'>Quantity: </label><input type='text' name='quantity' id='quantity' value='" . $obj->getQuantity() . "'>
                  <br><br><label for='description'>Description: </label><input type='text' name='description' id='description' value='" . $obj->getDescription() . "'>
                  <br><br><label for='onsale'>On Sale: </label><input type='text' name='onsale' id='onsale' value='" . $obj->getOnSale() . "'>
                  <br><br><label for='saleprice'>Sale Price: </label><input type='text' name='saleprice' id='saleprice' value='" . $obj->getSalePercent() . "'>";
    }
    $html .= "<br><br><button class='btn btn-primary' name='submit' value='update'>Submit</button></form></div></div>";
    return $html;

}

function validate($dbObj){
    //get count
    $count = $dbObj->getSalesCount();
    $error = "";

    $id = $_POST['id']; //never manipulated
    $onsale = $_POST['onsale'];
    //validate onsale
    if($onsale != 1 && $onsale != 0){ $error .= "On Sale must be a 0 or 1. 0 = false & 1 = true <br>"; }
    else{
        $res = $dbObj->checkExists( "SELECT t1.itemid FROM item t1 JOIN ON inventory t2 WHERE t2.onsale = :onsale AND itemId = :itemId" ,array(":onsale" => 1, ":itemId"=> $id));
        if($res != 0){ $error = "This item is already on sale <br>"; }
        if(!($count >= 3 || $count <=5)){ $error .=" You must have no more than 5 items on sale or no less than 3 items on sale <br>"; }
    }


    $name = santitize($_POST['name']);
    $quantity = santitize($_POST['quantity']);
    if($quantity < 0){ $error .= "Quantity cannot be less than zero <br>";}

    $description = santitize($_POST['description']);
    $saleprice = santitize($_POST['saleprice']);
    $price = santitize($_POST['price']);
    $image = santitize($_POST['image']);

    if($error !== "" ){
        return "<div class='container'><h3>" . $error . "</h3><br></div>";
    }else{
        $item = array($id, $name, $image, $price, $description, $quantity, $onsale, $saleprice);
        updateItem($dbObj, $item);
        return "<div class='container'><h3>Update was successful!</h3><br></div>";
    }

}

/**
 * santitize input
 * @param $var
 * @return string
 */
function santitize($var){
    $var = trim($var);
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = htmlspecialchars($var);

    return $var;
}//end santitize

/**
 * update query
 * @param $dbObj
 * @param $item
 */
function updateItem($dbObj, $item){
    $dbObj->updateDeleteInsert("UPDATE item SET name = :name, description = :description, image = :image WHERE itemid = :id", array(":name"=>$item[1],
        ":description"=>$item[4], ":image"=>$item[2], ":id"=>$item[0]));

    $dbObj->updateDeleteInsert("UPDATE inventory SET quantity = :quantity, price = :price WHERE itemid = :id",
        array(":quantity"=>$item[5], ":price"=>$item[3], ":id"=>$item[0]));

    $dbObj->updateDeleteInsert("UPDATE itemsale SET onsale = :onsale, salePrice = :salePrice WHERE itemid = :id",
        array(":onsale"=>$item[6], ":salePrice"=>$item[7], ":id"=>$item[0]));

}

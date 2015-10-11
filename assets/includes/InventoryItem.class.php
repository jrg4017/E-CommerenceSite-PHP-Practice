<?php

/**
 * Class InventoryItem
 * a class designed to hold the information retrieved from the database
 * to be displayed on the e-commerence site
 */
class InventoryItem{
    private $name,$price,$image,$description,$quantity;

    /**
     * set the object values
     * @param $name
     * @param $price
     * @param $image
     * @param $description
     * @param $quantity
     */
    public function __construct($arr){
        //TODO pass in as an array and iterate? for cleaner code??

        //set values
        $this->name = $arr[0];
        $this->price = $arr[1];
        $this->image = $arr[2];
        $this->description = $arr[3];
        $this->quantity = $arr[4];

    }//close __construct

    //TODO magic accessors / modifers? ask professor if okay to shorten code??

    /**************** MODIFERS *****************************************/
    public function setName($name){
        $this->name = $name;
    }//end setName

    public function setPrice($price){
        $this->price = $price;
    }//end setprice

    public function setImage($image){
        $this->image = $image;
    }//end setImage

    public function setDescription($description){
        $this->description = $description;
    }//end setDescription

    public function setQuantity($quantity){
        $this->quantity = $quantity;
    }//end setQuantity

    /**************** ACCESSORS *****************************************/
    public function getName(){
        return $this->name;
    }//end getName

    public function getPrice(){
        return $this->price;
    }//end getPRice

    public function getImage(){
        return $this->image;
    }//end getImage

    public function getDescription(){
        return $this->description;
    }//end getDescription

    public function getQuantity(){
        return $this->quantity;
    }//end getQuantity

    /**************** FUNCTIONS *****************************************/

}//end InventoryItem class
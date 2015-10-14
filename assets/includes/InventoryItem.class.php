<?php

/**
 * Class InventoryItem
 * a class designed to hold the information retrieved from the database
 * to be displayed on the e-commerence site
 */
class InventoryItem{
    protected $id, $name,$price,$image,$description,$quantity,$onSale,$salePrice;

    /**
     * @param $arr
     */
    public function __construct($arr){
        //TODO pass in as an array and iterate? for cleaner code??

        //set values
        $this->id = $arr[0];
        $this->name = $arr[1];
        $this->image = $arr[2];
        $this->price = $arr[3];
        $this->description = $arr[4];
        $this->quantity = $arr[5];
        $this->onSale = $arr[6];
        $this->salePrice = $arr[7];
    }//close __construct

    //TODO magic accessors / modifers? ask professor if okay to shorten code??

    /**************** MODIFERS *****************************************/
//    public function setId($id){
//        $this->id = $id;
//    }
//    public function setName($name){
//        $this->name = $name;
//    }//end setName
//
//    public function setPrice($price){
//        $this->price = $price;
//    }//end setprice
//
//    public function setImage($image){
//        $this->image = $image;
//    }//end setImage
//
//    public function setDescription($description){
//        $this->description = $description;
//    }//end setDescription
//
//    public function setQuantity($quantity){
//        $this->quantity = $quantity;
//    }//end setQuantity
//
//    public function setSalePrice($salePrice){
//        $this->salePrice = $salePrice;
//    }//end setSalePrice
//
//    public function setOnSale($onSale){
//        $this->onSale = $onSale;
//    }//end setOnSale

    /**************** ACCESSORS *****************************************/
    public function getId(){
        return $this->id;
    }//end getId

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

    public function getOnSale(){
        return $this->onSale;
    }//end get OnSale

    public function getSalePrice(){
        /* sale price is consider to be a percentage off so 10 -> 10% off
         * subtract % from 1 to get what the total sale price is
         * aka .9 * 10 = 9 is the equivalent of .1 * 10 = 1, then 10-1 = 9
        */

        if($this->onSale == 1) {
            $percent = 1 - ($this->salePrice * .01);
            $actualSalePrice = $this->price * $percent;
        }else{ $actualSalePrice = 0; }

        return round($actualSalePrice,2);

    }//end getSalePrice
    /**************** FUNCTIONS *****************************************/

}//end InventoryItem class
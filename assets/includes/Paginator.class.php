<?php

/**
 * Class Paginator
 * for handling the php pagination of sale, catalog, and cart items
 */
    class Paginator{
        private $itemsArray, $total, $page, $totalPrice;
        private $limit = 5;

        /**
         * save the array of InventoryItem objects and set
         * the total to the number of objects in the array
         * @param $itemArr
         */
        public function __construct($itemArr){
            $this->itemsArray = $itemArr;
            $this->total = count($this->itemsArray);
        }//end __construct

        /**
         * get the total price
         * @return mixed
         */
        public function getTotalPrice(){
            return $this->totalPrice;
        }//end getTotalPrice

        /**
         * get the data out of the array and display the current ones
         * @param int $page
         * @return array
         */
        public function getData($page = 1){
            $this->page = $page; //starts at 1 as defualt
            //empty results array to contain the objects for display
            $results = array();

            //get the end and start points of 5 items in the array for the pagination results
            $endIndex = ($page * 5);
            $startIndex = $endIndex - 5;

            //for the next five items in the array, load for preview. if the object is null then don't load into array
            for($i = $startIndex; $i < $endIndex; $i++ ){
                if($this->itemsArray[$i] != null) { $results[] = $this->itemsArray[$i]; }
            }

            return $results;

        }//end getData function

        /**
         * creates the pagination links
         * @param int $page - default is one
         * @return string
         */
        public function createPageLink($page = 1){
            //set the last available pagination page
            $last       = ceil( $this->total / $this->limit );

            $html = "<ul class='pagination'>";


            for($i = 0; $i < $last; $i++){
                $html .= "<li";

                //show active page
                if($page == ($i + 1)){ $html .= " class='active' "; }

                $html .= "><a href='?page=" . ($i+1) ."'>" . ($i + 1). "</a></li>";
            }

            $html .="</ul>";

            return $html;
        }//end createLink

        /**
         * displays the actual objects in a grid format
         * @param int $currPage - default is one
         * @param $sale
         * @param $cart
         * @return string
         */
        public function displayPagination($currPage = 1, $sale, $cart){
            $css_arr = array(1=>8, 2=>5, 3=>4, 4 => 3, 5 =>2); //for custom grid display, flexible
            $html = "<div class='row'>"; //start of row

            //TODO validate page number

            //get the info to display in 5s and the count of results
            $results = $this->getData($currPage);
            $count = count($results);

            //for each result obj, display in a grid with pagination
            for($i  = 0; $i < $count; $i++){
                $obj = (object)$results[$i];

                $html .= "<div class='col-sm-" .$css_arr[$count] ." inventoryItem'><form action=''>";

                //read out appropiate html for page
                if($cart != true) { $html .= $this->displayIndex($obj); }
                else{ $html .= $this->display($obj); }

                $html .= "</form></div>";

                //for calculating price
                if($obj->getSalePrice() > 0){
                    $this->totalPrice += $obj->getSalePrice();
                }else{
                    $this->totalPrice += $obj->getPrice();
                }
            }

            //get button if cart isn't empty or read out message if it is
            $html .= $this->emptyCart($count, $cart);

            //all catalogs except sale display pagination (sale can have 5 max so never a need)
            $html .= "<br><div class='col-lg-8'>" . $this->createPageLink($currPage) . "</div>";

            //return information
            return $html;
        }//end displayPagination

        /**
         * modular function with basic information display whether cart, sale, catalog
         * @param $obj
         * @return string
         */
        public function display($obj){
            $html = "<h3>" . $obj->getName() . "</h3>";
            $html .= "<span><em>Original price:</em> $" . $obj->getPrice() . "</span>";
            $html .= "<br><span><em>Sale Price:</em> $" . $obj->getSalePrice() . "</span>";
            $html .= "<br><span><em>Quantity:</em> " . $obj->getQuantity() . "</span>";
            $html .= "<br><span><em>Description:</em> " . $obj->getDescription() . "</span>";

            return $html;
        }//end display

        /**
         * additional image and button add to cart if on index page and not cart page
         * @param $obj
         * @return string
         */
        public function displayIndex($obj){
            $html = "<a href='". $obj->getImage() . "'><img src='" . $obj->getImage(). "' alt='" . $obj->getName() . "' height='150' width='200' /></a>";

            $html .= $this->display($obj);

            //if for cart page, don't show
            $html .= "<br><br><button class='btn btn-primary'  name='addToCart' value='" . $obj->getId() . "' >Add to Cart</button>";

            return $html;
        }//end display index

        /**
         * determines whether to display empty cart button or a message that the cart IS empty
         * @param $count
         * @param $cart
         * @return string
         */
        public function emptyCart($count, $cart){
            $html = "";
            //if at cart page ($cart = true), on click, clear cart with link
            //if cart is 0 don't display button and print out message
            if($cart == true && $count !== 0){ $html .= "<div class='col-lg-8'><h3>Total Price: $" . $this->getTotalPrice() . "</h3></div><div class='col-lg-6'><br><a class='btn btn-primary'  name='clearCart' href='?clearCart=true' >Empty Cart</a></div>";}

            if($count === 0 ){ $html .= "<h2>You currently have no items in your cart!</h2><div class='col-lg-6'><br><a class='btn btn-primary'  href='index.php' >Go back</a></div>"; }

            return $html;
        }//end emptyCart

    }//end Paginator class

?>
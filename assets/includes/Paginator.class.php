<?php

    class Paginator{
        private $itemsArray, $total, $page;
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

        public function setPage($page){
            $this->page = $page;
        }//end setPage

        /**
         * get the data out of the array and display the current ones
         * @param $page
         * @return array
         */
        public function getData($page = 1){
            $this->page = $page; //starts at 1

            //empty results array to contain the objects for display
            $results = array();

            //get the end number of 5 items in the array for the pagination results
            $endIndex = ($page * 5);

            $startIndex = $endIndex - 5;

            for($i = $startIndex; $i < $endIndex; $i++ ){
                if($this->itemsArray[$i] != null) {$results[] = $this->itemsArray[$i];}
            }

            return $results;

        }//end getData function

        /**
         *
         * @param int $page - default is one
         * @return string
         */
        public function createPageLink($page = 1){
            //TODO just do get next five pages instead of pagination @ bottom

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
         * @param int $currPage - default is one
         * @param $sale
         * @return string
         */
        public function displayPagination($currPage =1, $sale, $cart){
            $css_arr = array(1=>6, 2=>5, 3=>4, 4 => 3, 5 =>2);
            $html = "<div class='row'>";

             //get the info to display
            $results = $this->getData($currPage);
           $count = count($results);
            if($cart === true){ $html .= "<form action=''>";}
            for($i  = 0; $i < $count; $i++){
                $obj = (object)$results[$i];

                $html .= "<div class='col-sm-" .$css_arr[$count] ." inventoryItem'>";

                $html .= "<form action=''><a href='". $obj->getImage() . "'><img src='" . $obj->getImage(). "' alt='" . $obj->getName() . "' height='150' width='200' /></a>";
                $html .= "<h3>" . $obj->getName() . "</h3>";
                $html .= "<span> <em>Original price:</em> $" . $obj->getPrice() . "</span>";
                $html .= "<br><span><em>Sale Price:</em> $" . $obj->getSalePrice() . "</span>";
                $html .= "<br><span><em>Quantity:</em> " . $obj->getQuantity() . "</span>";
                $html .= "<br><span><em>Description:</em> " . $obj->getDescription() . "</span>";
                //if for cart page, don't show
                if($cart != true) { $html .= "<br><br><button class='btn btn-primary'  name='addToCart' value='" . $obj->getId() . "' >Add to Cart</button>"; }
                $html .= "</div>";
            }
            //TODO get cart to clear
                if($cart === true){ $html .= "</form><div class='col-lg-6'><button class='btn btn-primary'  name='clearCart' value='clear' >Empty Cart</button></div>";}
            $html .= "</form></div>";
            if($sale == false){
                $html .= $this->createPageLink($currPage);
            }
            return $html;
        }//end displayPagination
    }//end class

?>
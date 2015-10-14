<?php
    //include for inventory item object
    include "InventoryItem.class.php";

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
        public function getData($page){
            $this->page = $page; //starts at 1

            //empty results array to contain the objects for display
            $results = array();

            //get the end number of 5 items in the array for the pagination results
            $endIndex = ($page * 5);

            $startIndex = $endIndex - 5;

            for($i = $startIndex; $i < $endIndex; $i++ ){
                $results[] = $this->itemsArray[$i];
            }

            return $results;

        }//end getData function

        public function createPages($page){
            //TODO just do get next five pages instead of pagination @ bottom

            //set the last available pagination page
            $last       = ceil( $this->total / $this->limit );

            $html = "<ul class='pagination'>";


            for($i = 0; $i < $last + 1; $i++){
                $html .= "<li";

                //show active page
                if($page == $i){ $html .= "class='active'"; }

                $html .= "><a href='#' onclick='<?php $paginator->setPage(" . $i . "); ?>'>" . $i . "</a></li>";
            }


            return $html;
        }//end createLink

        public function displayPagination($currPage){


            $html = "<div class='row'>";

            $results = $this->getData($currPage);

            foreach($results as $item){
                $obj = new InventoryItem($item);

                $html .= "<div class='col-md-5'>";

                $html .= "<img src='" . $obj->getImage() . "alt='" . $obj->getName() . "' />";
                $html .= "<h4>" . $obj->getName() . "</h4>";
                $html .= "<p class='salePrice'>Sale Price:" . $obj->getSalePrice() . "</p><p class='originalSale'> Original price:" . $obj->getPrice() . "</p>";
                $html .= "<p>Quantity: " . $obj->getQuantity() . "</p>";
                $html .= "<p class='description'>Description:" . $obj->getDescription() . "</p>";

                $html .= "</div>";
            }

            $html .= "</div>" . $this->createPages($currPage);
        }
    }



?>
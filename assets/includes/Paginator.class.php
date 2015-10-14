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


            for($i = 0; $i < $last; $i++){
                $html .= "<li";

                //show active page
                if($page == $i){ $html .= "class='active'"; }

                $html .= "><a href='#' onclick='<?php $paginator->setPage(" . $i . "); ?>'>" . $i . "</a></li>";
            }


            return $html;
        }//end createLink

        public function displayPagination($currPage, $sale){
            $css_arr = array(4 => 3, 5 =>2);
            $html = "<div class='row'>";

            $results = $this->getData($currPage);
            if($sale == true) { $count = count($results) -1 ; }
            else{$count = count($results);}

            for($i  = 0; $i < $count; $i++){
                $obj = (object)$results[$i];

               // if($sale == true){ $count -= 1; }

                $html .= "<div class='col-sm-" .$css_arr[$count] ." inventoryItem'>";

                $html .= "<a href='". $obj->getImage() . "'><img src='" . $obj->getImage(). "' alt='" . $obj->getName() . "' height='150' width='200' /></a>";
                $html .= "<h3>" . $obj->getName() . "</h3>";
                $html .= "<span> <em>Original price:</em> $" . $obj->getPrice() . "</span>";
                $html .= "<br><span><em>Sale Price:</em> $" . $obj->getSalePrice() . "</span>";
                $html .= "<br><span><em>Quantity:</em> " . $obj->getQuantity() . "</span>";
                $html .= "<br><span><em>Description:</em> " . $obj->getDescription() . "</span>";
                $html .= "<br><br><button class='btn btn-primary'>Add to Cart</button>";
                $html .= "</div>";
            }

            $html .= "</div>";

            return $html;
        }//end displayPagination
    }//end class

?>
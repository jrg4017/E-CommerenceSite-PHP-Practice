<?php
    class Database{
        private $dbh;

        public function __construct(){
           $this->openDbh();
        }

        /**
         * upon destruction of object, set to null to destroy dbh
         */
        public function __destruct(){
            $this->closeDbh();
        }
        /** MUTATOR */
        /**
         * set the
         */
        public function setDbh($dbh){
            $this->dbh = $dbh;
        }//end setDbh

        /** ACCESORS */
        public function getDbh(){
            return $this->dbh;
        }//end getDbh

        /******** FUNCTIONS ***********/

        /**
         * opens a database connection object and returns it
         * if the database fails to open, returns null and program catches later
         * @return PDO
         */
        public function openDbh(){
            require_once "../../../../db_info.php";

            try {
                $dbh = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            }catch (PDOException $e){
                echo "<h2>Unexpected Error. Please try another time</h2>h2>";
                $dbh = null;
            }

            //set the dbh
            $this->setDbh($dbh);
        }//end openDbh

        /**
         * set to null to close
         */
        public function closeDbh(){
            $this->dbh = null;
        }//end closeDbh

        /**
         * updates the function based on query and params
         *      print out message based on success or failure
         * @param $query - the query specifics to update
         * @param $param - the params to use, array s
         * @return string - the string msg to return
         */
        function updateDeleteInsert($query, $param){
            //query function
            try { //TODO see if item exists first
                $stmnt = $this->dbh->prepare($query);
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
         * select statement
         * @param $query
         * @param null $params
         * @return mixed
         */
        function select($query, $params = null){
            $stmt =$this->getDbh()->prepare($query);

            if($params != null){ //if null then don't bind
                foreach($params as $key=>$val){
                    $stmt->bindParam($key, $val);
                }
            }
            $stmt->execute();
            $rs = $stmt->fetchAll();

            return $rs;
        }//end select

        /**
         * gets the number of current
         * @return $count - the number rows in sales
         */
        function getSalesCount(){
            //prepare query and
            $query = "SELECT name FROM ITEMSALE WHERE onsale = true";

            //prepare and run
            $stmt = $this->dbh->prepare($query);
            $stmt->execute();

            //return the number of rows already a sales item
            return $stmt->rowCount();
        }//end getSalesCount

        /**
         * check to see if variable exists and return true if does (greater than 0 returned)
         * return false if 0
         * @param $query
         * @param $params
         * @return mixed - return the number of rows
         */
        function checkExists($query, $params){
            try{
                $stmnt = $this->getDbh()->prepare($query);

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

    }//end Database class
?>
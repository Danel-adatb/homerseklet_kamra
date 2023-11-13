<?php
    
    class Database {
        //Static változókat használunk, mivel static function-ökből fogjuk elérni azokat az adatokat, 
        //és a nem static változtókat (ibjektumokat) így nem érhetjük el.
        protected static $connection;
        protected static $instance;
        protected static $table;
        protected $query;
        protected $query_type;
        protected $values = array();

        public static function connection($table) {
            self::$table = $table;

            if (!self::$instance) {
                self::$instance = new self();
            }

            if(!self::$connection) {
                try {
                    $pdo = 'mysql:host='.__DB_SERVER__.';dbname='.__DB_NAME__;
                    self::$connection = new PDO($pdo,__DB_USER__, __DB_PASS__);
                } catch(PDOException $e) {
                    echo $e->getMessage();
                    die;
                }
            }

            return self::$instance;
        }
    
        //Protected function mivel ezt csak ebből a class-ból fogjuk meghívni mindig
        protected function sql_run($values = array()) {
            $statement = self::$connection->prepare($this->query);
            $check = $statement->execute($values);

            if($check) {
                switch($this->query_type) {
                    case 'SELECT':
                            $results = $statement->fetchAll(PDO::FETCH_OBJ);
                            if(is_array($results) && count($results) > 0) {
                                return $results;
                            }
                        break;

                    case 'INSERT':
                        //Things went well
                        return true;
                        break;

                    case 'UPDATE':
                        //Things went well
                        return true;
                        break;

                    case 'DELETE':
                        //TODO
                        break;
                }
            }

            //Things not went well
            return false;
        }

        public function sql_select() {
            $this->query_type = "SELECT";
            $this->query = "SELECT * FROM " . self::$table . " ";
            return self::$instance;
        }

        public function sql_select_all() {
            return $this->sql_run();
        }

        public function sql_where($where, $values = array()) {
            switch($this->query_type) {
                case 'SELECT':
                        $this->query .= " WHERE " . $where;
                    break;

                case 'UPDATE':
                        $values = array_merge($this->values, $values);
                        $this->query .= " WHERE " . $where;
                    break;
            }

            
            return $this->sql_run($values);
        }

        public function sql_update(array $values) {
            $this->query_type = "UPDATE";
            $this->query = "UPDATE ". self::$table . " SET ";

            foreach($values as $key => $value) {
                $this->query .= $key .= "= :" . $key . ",";
            }

            $this->query = trim($this->query, ',');
            $this->values = $values;

            return self::$instance;
        }

        public function sql_insert(array $values) {
            $this->query_type = "INSERT";
            $this->query = "INSERT INTO " . self::$table . " (";

            //Columns
            foreach($values as $key => $value) {
                $this->query .= $key . ",";
            }

            $this->query = trim($this->query, ",");
            $this->query .= ") VALUES (";

            //Values
            foreach($values as $key => $value) {
                $this->query .= ":" . $key . ",";
            }

            $this->query = trim($this->query, ",");
            $this->query .= ")";

            $this->values = $values;

            return $this->sql_run($this->values);
        }

        public function sql_query($query, $values = array()) {
            $statement = self::$connection->prepare($query);
            $check = $statement->execute($values);

            if($check) {
                $results = $statement->fetchAll(PDO::FETCH_OBJ);
                if(is_array($results) && count($results) > 0) {
                    return $results;
                }
            }

            return false;
        }
       
    }

?>
<?php
    class Chamber {
        protected static $instance;

        public static function check_instance() {
            if(!self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function get_chamber_data()  {
            return Database::connection('chambers')->sql_select()->sql_select_all();
        }
    }
?>
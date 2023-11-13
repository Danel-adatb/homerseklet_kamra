<?php
    class Chart {
        protected static $instance;

        public static function check_instance() {
            if(!self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function get_chart_data()  {
            return Database::connection('temperatures')->sql_select()->sql_select_all();
        }
    }
?>
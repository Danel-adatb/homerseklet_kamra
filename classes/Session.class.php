<?php
    class Session {
        function __construct() {
            //TODO
        }

        private function start() {
            if(!isset($_SESSION)) {
                session_start();
            }
        }

        public function flush() {
            $this->start();
            session_destroy();
        }

        public function set($key, $value = '') {
            $this->start();

            if(is_string($key)) {
                $_SESSION[$key] = $value;
            } elseif(is_array($key)) {
                foreach($key as $k => $v) {
                    $_SESSION[$k] = $v;
                }
            }
        }

        public function exists($key) {
            $this->start();

            if(isset($_SESSION[$key])) {
                return true;
            }

            return false;
        }

        public function get($key) {
            $this->start();

            if(isset($_SESSION[$key])) {
                return $_SESSION[$key];
            }
        }

        public function remove($key) {
            $this->start();

            if(isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
                return true;
            }

            return false;
        }

        public function regenerate() {
            session_regenerate_id();
        }
    }
?>
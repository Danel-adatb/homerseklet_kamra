<?php

    class User {

        protected static $instance;

        public static function check_instance() {
            if(!self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function is_user_logged_in() {
            $session = new Session();

            if($session->exists('USER')) {
                $data = $session->get('USER');
                $email = $data['email'];
                $data = $this->get_user_by_email($email);
                
                if(is_array($data)) {
                    return true;
                } 
            }

            return false;
        }

        public function is_user_admin() {
            $session = new Session();

            if($session->exists('USER')) {
                $data = $session->get('USER');
                $role = trim($data['role']);
                
                if($role == 'admin') {
                    return true;
                } else {
                    return false;
                }
            }

            return false;
        }

        public function get_user_all() {
            return Database::connection('users')->sql_select()->sql_select_all();
        }

        //Get data with column name (get_user_by_id/get_user_by_email)
        public function __call($func, $parameters) {
            $value = $parameters[0];
            $col = str_replace('get_user_by_', '', $func);
            $col = addslashes($col);

            //If column exists
            $check = Database::connection('users')->sql_query('SHOW COLUMNS FROM USERS');
            $all_cols = array_column($check, 'Field');

            if(in_array($col, $all_cols)) {
                return Database::connection('users')->sql_select()->sql_where($col . ' = :' . $col, [$col => $value]);
            }

            return false;
        }

        public function create_user($POST) {
            
            $errors = array(
                'email_error' => '',
                'password_error' => '',
                'chamber_id_error' => '',
                'role_error' => ''
            );

            $a['email'] = trim($POST['email']);
            $a['password'] = trim($POST['password']);
            $a['chamber_id'] = trim($POST['chamber']);
            $a['role'] = trim($POST['role']);

            echo '<pre>';
            print_r($a);
            echo '</pre>';

            $email_regex = '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/i';
            $password_regex = '/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/';
            $chamber_regex = '/^[A-Za-z0-9]*$/';
            //$sql_injection_regex = '/^(?!.*[;\\<>\?!$<script>\$])(?=.*\.).*$/s';

            //Validation
            if(empty($a['email']) || !preg_match($email_regex, $a['email'])) {
                $errors['email_error'] = 'Kérem a megfelelő e-mail formátumot használja (pelda@email.hu)';
            }

            if(empty($a['password']) || !preg_match($password_regex, $a['password'])) {
                $errors['password_error'] = 'Jelszó követelmények: Egy szám, legalább egy karakter, megengedett karakterek [!@#$%], 8-12 karakter hossz!';
            }

            if(empty($a['chamber_id']) || !preg_match($chamber_regex, $a['chamber_id'])) {
                $errors['chamber_id_error'] = 'Kamra azonosító: Számok és betúk (Nagybetű érzékeny)!';
            }

            if($a['role'] == 'choose' || ($a['role'] != 'admin' && $a['role'] != 'user')) {
                $errors['role_error'] = 'Szerepkör hiba!';
            }
            
            if(empty($errors['email_error']) && empty($errors['password_error']) && empty($errors['chamber_id_error']) && empty($errors['role_error'])) {
                return Database::connection('users')->sql_insert($a);
            }

            echo '<pre>';
            print_r($errors);
            echo '</pre>';

            return $errors;
        }


        public function login($POST) {
            $errors = array();
            //$sql_injection_regex = '/^(?!.*[;\\<>\?!$<script>\$])(?=.*\.).*$/s';

            $a['email'] = trim($POST['email']);
            $password = $POST['password'];
            $data = Database::connection('users')->sql_select()->sql_where('email = :email', $a);

            //TODO
            //Validation
            /*if(!preg_match($sql_injection_regex, trim($POST['email'])) && !preg_match($sql_injection_regex, trim($POST['password']))) {
                //SQL_injection log
            }*/
            
            if(is_array($data)) {
                $data = $data[0];

                if($data->password == $password) {
                    $session = new Session();
                    $session->regenerate();

                    $a['user_id'] = $data->id;
                    $a['email'] = $data->email;
                    $a['chamber_id'] = $data->chamber_id; 
                    $a['role'] = $data->role;
                    $a['LOGGED_IN'] = 1;

                    $session->set('USER', $a);

                    return true;
                }
            } 

            $errors[] = 'Wrong email or password';

            return $errors;
        }

        public function delete_user() {

        }

        public function update_user_by_id($values = array(), $id) {
            return Database::connection('users')->sql_update($values)->sql_where('id = :id', ['id' => $id]);
        }
    }

?>
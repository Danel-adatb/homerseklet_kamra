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

            $a['email'] = trim(addslashes($POST['email']));
            $a['password'] = trim(addslashes($POST['password']));
            $a['chamber_id'] = trim(addslashes($POST['chamber']));
            $a['role'] = trim(addslashes($POST['role']));

            $email_regex = '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/i';
            $password_regex = '/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@#^&_+-])[a-zA-Z0-9@#^&_+-]{8,12}$/';
            $chamber_regex = '/^[A-Za-z0-9]+$/';

            //Validation
            if(empty($a['email']) || !preg_match($email_regex, $a['email'])) {
                $errors['email_error'] = 'Kérem a megfelelő e-mail formátumot használja (pelda@email.hu)';
            }

            if(empty($a['password']) || !preg_match($password_regex, $a['password'])) {
                $errors['password_error'] = 'Jelszó követelmények: Egy szám és egy karakter, illetve egy megengedett karakterek (@, #, ^, &, _, +, -) karaker legalább (8-12 hossz)!';
            }

            if(empty($a['chamber_id']) || !preg_match($chamber_regex, $a['chamber_id'])) {
                $errors['chamber_id_error'] = 'Kamra azonosító: Legalább egy szám és/vagy betú (Nagybetű érzékeny)!';
            }

            if($a['role'] == 'choose' || ($a['role'] != 'admin' && $a['role'] != 'user')) {
                $errors['role_error'] = 'Szerepkör hiba!';
            }

            $check_email = Database::connection('users')->sql_select()->sql_where('email = :email', ['email' => $a['email']]);

            if(is_array($check_email) && count($check_email) > 0) {
                $errors['email_error'] = 'E-mail már használatban van!';
            }
            
            if(empty($errors['email_error']) && empty($errors['password_error']) && empty($errors['chamber_id_error']) && empty($errors['role_error'])) {
                $a['password'] = password_hash(trim($POST['password']), PASSWORD_DEFAULT);
                return Database::connection('users')->sql_insert($a);
            }

            return $errors;
        }


        public function login($POST) {
            $errors = array(
                'email_error' => '',
                'password_error' => '',
                'invalid_data' => ''
            );

            $a['email'] = trim(addslashes($POST['email']));
            $password = trim(addslashes($POST['password']));
            $data = Database::connection('users')->sql_select()->sql_where('email = :email', $a);

            $email_regex = '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/i';
            $password_regex = '/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@#^&_+-])[a-zA-Z0-9@#^&_+-]{8,12}$/';

            //Validation
            if(empty($a['email']) || !preg_match($email_regex, $a['email'])) {
                $errors['email_error'] = 'Kérem a megfelelő e-mail formátumot használja (pelda@email.hu)';
            }

            if(empty($a['password']) || !preg_match($password_regex, $a['password'])) {
                $errors['password_error'] = 'Kérem a megfelelő formátumot használja!';
            }
            
            if(is_array($data)) {
                $data = $data[0];

                if(password_verify($password, $data->password)) {
                    $session = new Session();
                    $session->regenerate();

                    $a['user_id'] = $data->id;
                    $a['email'] = $data->email;
                    $a['chamber_id'] = $data->chamber_id; 
                    $a['role'] = $data->role;

                    $session->set('USER', $a);

                    return true;
                }
            } 

            $errors['invalid_data'] = 'Nem megfelelő jelszó vagy e-mail cím!';

            return $errors;
        }

        public function delete_user() {

        }

        public function update_user_by_id($values = array(), $id) {
            return Database::connection('users')->sql_update($values)->sql_where('id = :id', ['id' => $id]);
        }
    }

?>
<?php
        
    if(session_id() == '') {
        session_start();
    }

    require_once 'api.php';

    if (empty($selectingDatabase)) {
        $thisDir = dirname(__FILE__);
        require_once( dirname($thisDir) . '/db_operations/db_connection.php');
    }

    class UserController extends API
    {
        /**
         * Example of an Endpoint
         */
        protected function user() {
            if ($this->method == 'GET') {

                if(!isset($_SESSION['user_id'])) {
                    //return "User not Authenticated! You must be logged in to view this resource.";
                    return self::response("User not Authenticated! You must be logged in to view this resource.", 401);
                }

                $id = $_SESSION['user_id'];
                
                $result = DB::$connect->query(
                    "SELECT * from user where id = '" . $id . "'"
                );

                while ($row = DB::$connect->fetchArray()) 
                {
                    //$new_array[$row['id']]['id'] = $row['id'];
                    $new_array['id'] = $row['id'];
                    //$new_array[$row['id']]['username'] = $row['username'];
                    $new_array['username'] = $row['username'];
                }
                
                return self::response($new_array);
                
                
            } else {
                //return "Only accepts GET requests";
                return self::response("Only accepts GET requests", 405);
            }
        }
        
        protected function login() {
            
            if ($this->method == 'POST') {
                
                if (empty($_POST['username']) || empty($_POST['password'])) {
                    
                    $data = json_decode(file_get_contents('php://input'), true);
                    
                    if (empty($data['username']) || empty($data['password'])) {
                        //return "Username or Password not provided!";
                        return $this->response("Username or Password not provided!", 400);
                    }

                    $_POST['username'] = $data['username'];
                    $_POST['password'] = $data['password'];
                }

                $username = $_POST['username'];
                $password = $_POST['password'];

                $password = sha1($password);

                //if (empty($selectingDatabase)) {
                //    include "db_operations/db_connection.php";
                //}

                $result = DB::$connect->query(
                    "SELECT id from user where username = '" . $username . "' && password = '"
                    . $password . "'"
                );

                $login = DB::$connect->numRows();

                if($login > 0){
                    $user_id =  DB::$connect->result(0);

                    if ($user_id) {
                        $_SESSION['user_id'] = $user_id;

                        //$this->response(array('ok'=>true, 'message'=>"Successful login!"));
                        return self::response(array('message'=>"Successful login!"));
                    }
                }
                
                //return "Invalid Credentials!";
                return self::response("Invalid Credentials!", 401);
                
                
            } else {
                //return "Only accepts POST requests";
                return self::response("Only accepts POST requests", 405);
            }
        }

        protected function logout() {
            
            if ($this->method == 'DELETE') {

                if(!isset($_SESSION['user_id'])) {
                    //return "Not LoggedIn!";
                    return self::response("Not LoggedIn!", 410);
                    //$this->response("Not LoggedIn!", 401);
                }
                
                $_SESSION['user_id'] = null;
                //return "Successfully Logged Out!";
                return self::response("Successfully Logged Out!");
            }
            else {
                return self::response("Only accepts DELETE requests", 405);
            }
        }
     }
?>
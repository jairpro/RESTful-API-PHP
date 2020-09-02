<?php
    
    if(session_id() == '') {
        session_start();
    }

    require_once 'api.php';
    
    if (empty($selectingDatabase)) {
        $thisDir = dirname(__FILE__);
        require_once( dirname($thisDir) . '/db_operations/db_connection.php');
    }

    class ItemController extends API
    {

        /**
         * Example of an Endpoint
         */
         protected function item($args, $file) {

            if(!isset($_SESSION['user_id'])) {
                return "User not Authenticated! You must be logged in to view this resource.";
            }

            if ($this->method == 'GET') {

                if (sizeof($args) > 0) {

                    $id = $args[0];
                    
                    DB::$connect->query(
                        "SELECT * from item where id = '" . $id . "'"
                    );

                    $itemExists = DB::$connect->numRows();

                    if($itemExists < 1)
                        return self::response("No item found with id: " . $args[0], 404);

                    while ($row = DB::$connect->fetchArray()) 
                    {
                        $new_array['id'] = $row['id'];
                        $new_array['name'] = $row['name'];
                        $new_array['price'] = $row['price'];
                    }

                    return $new_array;
                }
                
                
                DB::$connect->query(
                    "SELECT * from item"
                );

                $new_array = array();
                while ($row = DB::$connect->fetchArray()) 
                {
                    $new_registry = array();
                    $new_registry['id'] = $row['id'];
                    $new_registry['name'] = $row['name'];
                    $new_registry['price'] = $row['price'];
                    $new_array[] = $new_registry;
                }

                return $new_array;
            }

            if ($this->method == 'POST') {
                
                if (empty($_POST['name']) || empty($_POST['price'])){

                    $data = json_decode(file_get_contents('php://input'), true);

                    if (empty($data['name']) || empty($data['price'])) {
                        return self::response("Name and price of the item to be added are required!", 400);
                    }

                    $_POST['name'] = $data['name'];
                    $_POST['price'] = $data['price'];
                    
                }
                
                $name = $_POST['name'];
                $price = $_POST['price'];

                if($price < 0) {
                    return self::response("Price of your item can't be zero or negative! You don't want to sell it for free. Do you?", 400);
                }

                $result = DB::$connect->query(
                    "INSERT INTO item (name, price) VALUES ( '" . $name . "', '"
                         . $price . "')"
                );

                if(!$result) {
                    return self::response("Error adding item!", 500);
                }
                else {
                    return self::response(array(
                        'message' => "Item added successfully!",
                        'data' => array(
                            'id' => DB::$connect->getInsertId(),
                            'name' => $name,
                            'price' => $price
                        )
                    ));
                }
            }

            if ($this->method == 'PUT') {

                $data = json_decode($file, true);

                if(empty($data['name']) || empty($data['price'])) {
                    return self::response("Name and price of the item to be updated are required!", 400);
                }

                $name = $data['name'];
                $price = $data['price'];

                if($price < 0) {
                    return self::response("Price of your item can't be zero or negative! You don't want to sell it for free. Do you?", 400);
                }

                if (sizeof($args) > 0) {
                    
                    $id = $args[0];

                    $result = DB::$connect->query(
                        "UPDATE item set name = '" . $name . "' , price = '" . 
                        $price . "' where id = '" . $id . "'"
                    );

                    if(!$result) {
                        return self::response("Error updating item!", 500);
                    }
                    else if(DB::$connect->getAffectedRows() < 1) {
                        return self::response("No item updated, possibly item not found with id: " . $id . "!", 404);
                    }
                    else {
                        return self::response("Item updated successfully!");
                    }
                }

                return self::response("'id' of the item to be updated is required. Add it to the end of url", 400);
            }

            if ($this->method == 'DELETE') {
                
                if (sizeof($args) > 0) {
                    $id = $args[0];

                    $result = DB::$connect->query(
                        "DELETE from item where id = '" . $id . "'"
                    );

                    if(!$result) {
                        return self::response("Error deleting item!", 500);
                    }
                    else if(DB::$connect->getAffectedRows() < 1) {
                        return self::response("No item deleted, possibly item not found with id: " . $id . "!", 404);
                    }
                    else {
                        return self::response("Item deleted successfully!");
                    }
                }

                return self::response("'id' of the item to be deleted is required. Add it to the end of url", 400);
            }
         }
     }
?>
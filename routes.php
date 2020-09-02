<?php
    //ob_start();

    //if(empty($_REQUEST['controller'])) {
    if(empty($_REQUEST['request'])) {
        //echo 'Hey! You just got 404\'D. Did you just come up with that url by your own?';
        echo 'Ei! Você acabou de obter o 404\'D. Você acabou de criar esse url sozinho?';
        return header("HTTP/1.1 404 Not Found");
    }

    require "api/user.php";
    require "api/item.php";

    // Requests from the same server don't have a HTTP_ORIGIN header
    if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
        $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
    }

    try {

        //$controller = isset($_REQUEST['controller']) ? $_REQUEST['controller'] : null;
        $request = isset($_REQUEST['request']) ? $_REQUEST['request'] : null;
        
        $tempRequest = $request;
        $aRequest = explode("/", $request);
        $controller = isset($aRequest[0]) ? $aRequest[0] : null;
        $request = implode("/", array_slice($aRequest,1));
        //echo "tempRequest: '$tempRequest'<br>";
        //echo "controller: '$controller'<br>";
        //echo "request: '$request'<br>";

        if(empty($controller)) {
            header("HTTP/1.1 404 Not Found");
            return json_encode("No route found!");
        }

        if (empty($request)) {
            $request = $controller;
        } else if(ctype_digit($request)) {
            $request = $controller . '/' . $request;
        }

        switch($controller) {

            case 'user':
                $API = new UserController($request);
                echo $API->processAPI();
                break;

            case 'item':
                $API = new ItemController($request);
                echo $API->processAPI();
                break;

            default:
                //echo 'Hey! You just got 404\'D. Did you just come up with that url by your own?';
                echo 'Ei! Você acabou de obter o 404. Você acabou de criar esse url sozinho?';
                return header("HTTP/1.1 404 Not Found");
        }

        
    } catch (Exception $e) {
        //echo json_encode(Array('error' => $e->getMessage()));
        return API::response(Array('error' => $e->getMessage()), 500);
    }

?>
<?php
    ob_start();
    try {

        include ".env.php";

        ini_set("display_errors", $env==='development');
        ini_set("error_log", "php_errors.log");

        require "api/api.php";

        if(empty($_REQUEST['request'])) {
            return API::response("Hello API!");
        }

        require "api/user.php";
        require "api/item.php";

        // Requests from the same server don't have a HTTP_ORIGIN header
        if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
            $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
        }

        try {

            $request = isset($_REQUEST['request']) ? $_REQUEST['request'] : null;
            
            $tempRequest = $request;
            $aRequest = explode("/", $request);
            $controller = isset($aRequest[0]) ? $aRequest[0] : null;
            $request = implode("/", array_slice($aRequest,1));

            if(empty($controller)) {
                return API::response("No route found!", 404);
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
                    return API::response('Invalid route!', 404);
            }

            
        } catch (Exception $e) {
            return API::response(Array('error' => strip_tags($e->getMessage())), 500);
        }
    }
    catch(Exception $e) {
        return API::response(Array('error' => strip_tags($e->getMessage())), 500);
    }

    $displayError = strip_tags(trim(ob_get_clean()));
    if ($displayError) {
        return API::response("error on execute:/n".$displayError, 500);
    }
<?php


class ROUTE
{


    public static function get($route, $do)
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $params = array();

            $params_count = substr_count($route, '{');
            $slash_count = substr_count($route, '/');
            $slash_viola_count = substr_count(VIOLA_ROUTE, '/');


            $exp = explode('/', $route);
            $explode_route = explode('/', VIOLA_ROUTE);

            if ($params_count > 0) {
                $params = array_slice($explode_route, -$params_count, $params_count, true);
            }

            $c = $slash_count - $params_count;
            $cont = implode('/', array_slice($explode_route, 0, $c + 1, true));
            $cont_ = implode('/', array_slice($exp, 0, $c + 1, true));


            if ($slash_count == $slash_viola_count && $cont == $cont_) {
                $file = explode("->", $do);
                $class_name = $file[0];
                $action = $file[1];
                include_once ROOT . DS . "c" . DS . $class_name . ".php";
                $cls = new  $class_name();
                call_user_func_array(array($cls, $action), $params);
            }


        }
    }


}

?>

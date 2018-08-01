<?php


class Route
{

    public static $route;

    public static function get($_route = "", $do)
    {
        $route = new Route_();

        if ($_route == "") {
            $_route = "/";
        }


        $route->add($_route, $do);


        $route->submit();
    }


}


/**
 * @author        Jesse Boyer <contact@jream.com>
 * @copyright    Copyright (C), 2011-12 Jesse Boyer
 * @license        GNU General Public License 3 (http://www.gnu.org/licenses/)
 *                Refer to the LICENSE file distributed within the package.
 *
 * @link        http://jream.com
 *
 * @internal    Inspired by Klein @ https://github.com/chriso/klein.php
 */
class Route_
{
    /**
     * @var array $_listUri List of URI's to match against
     */
    private $_listUri = array();

    /**
     * @var array $_listCall List of closures to call
     */
    private $_listCall = array();

    /**
     * @var string $_trim Class-wide items to clean
     */
    private $_trim = '/\^$';

    /**
     * add - Adds a URI and Function to the two lists
     *
     * @param string $uri A path such as about/system
     * @param object $function An anonymous function
     */
    public function add($uri, $function)
    {
        $uri = trim($uri, $this->_trim);
        $this->_listUri[] = $uri;

        if (is_callable($function)) {
            $this->_listCall[] = $function;
        } else {
            $this->_listCall[] = "@" . $function;
        }
    }

    /**
     * submit - Looks for a match for the URI and runs the related function
     */
    public function submit()
    {
        $uri = isset($_REQUEST['uri']) ? $_REQUEST['uri'] : '/';
        $uri = trim($uri, $this->_trim);

        $replacementValues = array();

        /**
         * List through the stored URI's
         */
        foreach ($this->_listUri as $listKey => $listUri) {
            /**
             * See if there is a match
             */
            if (preg_match("#^$listUri$#", $uri)) {
                /**
                 * Replace the values
                 */
                $realUri = explode('/', $uri);
                $fakeUri = explode('/', $listUri);

                /**
                 * Gather the .+ values with the real values in the URI
                 */
                foreach ($fakeUri as $key => $value) {
                    if ($value == '.+') {
                        $replacementValues[] = $realUri[$key];
                    }
                }

                /**
                 * Pass an array for arguments
                 */
                if (is_callable($this->_listCall[$listKey])) {
                    call_user_func_array($this->_listCall[$listKey], $replacementValues);
                } else {
                    $file = explode(":", ltrim($this->_listCall[$listKey], "@"));
                    $class_name = $file[0];
                    $action = $file[1];
                    include_once ROOT . DS . "c" . DS . $class_name . ".php";
                    $cls = new $class_name();
                    call_user_func_array(array($cls, $action), $replacementValues);
                }
                exit;
            }

        }

    }

}


/*
class Route
{

    public static function get($route, $do)
    {


        if ($_SERVER['REQUEST_METHOD'] == "GET") {

            $braces_param_count = substr_count($route, '{');// coming from the ROUTE class
            $slashes_param_count = substr_count(VIOLA_ROUTE, '/'); // coming from the REQUEST

            $file = explode("->", $do);
            $class_name = $file[0];
            $action = $file[1];
            include_once ROOT . DS . "c" . DS . $class_name . ".php";
            $cls = new $class_name();

            if ($route == VIOLA_ROUTE) {
                //one to one access
                $cls->$action();
                exit;
            } else if ($braces_param_count == $slashes_param_count) {
                //if has params
                $explode_viola_route = explode('/', VIOLA_ROUTE);
                $args = array_slice($explode_viola_route, -$slashes_param_count, $braces_param_count, true);
                call_user_func_array(array($cls, $action), $args);
                exit;
            } else {
                if ($route != VIOLA_ROUTE) {
                    //raise error / no route
                    //$e = new E();
                    //echo $e->no_route();
                }
            }

        }

    }
}
*/

?>

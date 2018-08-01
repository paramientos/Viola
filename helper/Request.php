<?php


class Request
{

    public static function has($key)
    {
        return !empty(self::get($key)) && !is_null(self::get($key)) ? true : false;
    }

    public static function get($key)
    {
        $request = new Requestx();
        if (array_key_exists($key, $request->get)) {
            return $request->get[$key];
        } else {
            return null;
        }
    }

    public static function get_full_url()
    {
        return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

}


class Requestx
{
    public $get = array();
    public $post = array();
    public $cookie = array();
    public $files = array();
    public $server = array();

    public function __construct()
    {
        $_GET = $this->clean($_GET);
        $_POST = $this->clean($_POST);
        $_REQUEST = $this->clean($_REQUEST);
        $_COOKIE = $this->clean($_COOKIE);
        $_FILES = $this->clean($_FILES);
        $_SERVER = $this->clean($_SERVER);

        $this->get = $_GET;
        $this->post = $_POST;
        $this->request = $_REQUEST;
        $this->cookie = $_COOKIE;
        $this->files = $_FILES;
        $this->server = $_SERVER;
    }

    public function clean($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);

                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
        }

        return $data;
    }


}

?>
<?php


class Session
{

    public static function get($key)
    {
        $sess = new Sessionx();
        return $sess->data[$key];

    }

    public static function set($key, $value)
    {

        $sess = new Sessionx();
        $sess->data[$key] = $value;
    }


}


class Sessionx
{
    public $data = array();

    public function __construct()
    {
        if (!session_id()) {
            ini_set('session.use_only_cookies', 'On');
            ini_set('session.use_trans_sid', 'Off');
            ini_set('session.cookie_httponly', 'On');

            session_set_cookie_params(0, '/');
            session_start();
        }

        $this->data =& $_SESSION;
    }

    function getId()
    {
        return session_id();
    }

}

?>



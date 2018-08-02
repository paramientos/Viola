<?php


class Auth
{

    static protected $table = "users";
    static protected $userid_column = "user_id";
    static $username_column = "username";
    static protected $password_column = "password";
    public static $user_id = 0;

    private static function get_user_info()
    {
        $id = !empty(Session::get("user_id")) ? Session::get("user_id") : 0;
        $q = db()->query(format("select * from {0} where `{1}`='{2}' limit 1", Auth::$table, Auth::$userid_column, $id));
        if ($q->num_rows) {
            return $q;
        } else {
            return null;
        }
    }

    public static function id()
    {
        $q = Auth::get_user_info();
        if (!is_null($q)) {
            return $q->row[Auth::$userid_column];
        }
    }


    public static function username()
    {
        $q = Auth::get_user_info();
        if (!is_null($q)) {
            return $q->row[Auth::$username_column];
        }
    }

    public static function logout()
    {
        Session::set("logged", false);
        Session::set("user_id", 0);
        session_destroy();
    }

    public static function logged()
    {
        return Session::get("logged");
    }

    /**
     * @param string $username
     * @param string $password
     * @param bool $use_md5_for_password
     * @return bool
     */
    public
    static function login($username = "", $password = "", $use_md5_for_password = false)
    {
        $sql = format("select * from {0} where `{1}`='{2}' and `{3}`='{4}' limit 1", Auth::$table, Auth::$username_column, $username, $use_md5_for_password ? Auth::$password_column : Auth::$password_column, $use_md5_for_password ? md5($password) : $password);
        $q = db()->query($sql);
        if ($q->num_rows) {
            Session::set("logged", true);
            Session::set("user_id", $q->row[Auth::$userid_column]);
            return true;
        } else {
            session_destroy();
            return false;
        }

    }


}


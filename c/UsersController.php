<?php


class UsersController
{


    function get_users($a, $b, $c)
    {
        echo __FUNCTION__;
        echo $a;
    }

    function test()
    {
        echo "ok";
    }

    function get_user($id)
    {
        $data['a'] = "test";
        //view("users", $data);
        //$db = db();
        //$db->query("select * from users");

        $q=db()->query("select * from users");
        if ($q->num_rows) {
            var_dump($q->rows);
        }

        //$db->query("");
        //echo $id;
        //echo config("app.url");
        //helper('form.session');
        //Form::textbox("username", "Kullancı Adı", config("app.url"));
        //Session::set("app.url", config("app.url"));
        //echo Session::get("app.url");

    }


}
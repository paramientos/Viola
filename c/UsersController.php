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

        /* $q=db()->query("select * from users");
         if ($q->num_rows) {
             var_dump($q->rows);
         }*/

       var_dump(Auth::logged());



        /*helper("validate");
        $rules = [
            "name" => "required|string",
            "age" => "numeric",
            "consent" => "required_without:age"
        ];

        // Aliases for request parameters with special characters
        $aliases = [
            "name" => "Full Name"
        ];

        // Will either be true for success, or an array of error messages
        $result = Validate::validateGet($rules, $aliases);
        echo json_encode($result);*/
        //var_dump(Request::get_full_url());

        //echo language::get("title");

        //$db->query("");
        //echo $id;
        //echo config("app.url");
        //helper('form.session');
        //Form::textbox("username", "Kullancı Adı", config("app.url"));
        //Session::set("app.url", config("app.url"));
        //echo Session::get("app.url");

    }


}
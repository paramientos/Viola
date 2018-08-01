<?php




class Form
{

    public static function textbox($name, $label, $value = "")
    {
        echo "<input type=\"text\" name=\"$name\" id=\"$name\" value=\"$value\" />";
    }


}
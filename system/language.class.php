<?php


class Language
{

    public static function get($key)
    {
        $current_lang = config("app.language");
        if (file_exists(ROOT . DS . 'lang' . DS . $current_lang . '.php')) {
            $_ = array();
            include ROOT . DS . 'lang' . DS . $current_lang . '.php';
            if (array_key_exists($key, $_)) {
                return $_[$key];
            } else {
                error("no_language_key", $key);
            }
        } else {
            //error("no_language_file", $key);
            echo "NO <b>$current_lang</b> LANGUAGE FILE at " . ROOT . DS . 'lang' . DS;
        }

    }


}
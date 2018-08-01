<?php


if (!class_exists("E")) {

    class E
    {


        function call($e, $arg)
        {

            return str_replace('{0}', '{' . $arg . '}', Language::get($e));

        }


    }


}



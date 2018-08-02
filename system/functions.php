<?php


function db()
{
    include_once ROOT . DS . 'drivers' . DS . config("db.type") . '.php';
    $db_type = 'db_' . config("db.type");
    $db = new $db_type(config("db.host"), config("db.user"), config("db.pass"), config("db.db"));
    return $db;
}


function view($template = null, $data = array())
{
    if (file_exists(ROOT . DS . 'v' . DS . $template . '.php')) {

        if (!empty($data)) {
            extract($data);
        }

        include_once ROOT . DS . 'v' . DS . $template . '.php';
    } else {
        error("no_view_file", $template);
    }
}


function helper($helpers)
{
    $helpers_array = explode('.', $helpers);

    if (is_array($helpers_array)) {
        foreach ($helpers_array as $helper) {
            if (file_exists(ROOT . DS . 'helper' . DS . ucfirst($helper) . '.php')) {
                include_once ROOT . DS . 'helper' . DS . ucfirst($helper) . '.php';
            } else {
                error("no_helper_file", $helper);
            }
        }
    } else {
        if (file_exists(ROOT . DS . 'helper' . DS . ucfirst($helpers) . '.php')) {
            include_once ROOT . DS . 'helper' . DS . ucfirst($helpers) . '.php';
        } else {
            error("no_helper_file", $helpers);
        }
    }
}


// $config= config_file.array_key
function config($config)
{
    $exp = explode('.', $config);
    $config_file = $exp[0];
    $config_key = $exp[1];
    if (file_exists(ROOT . DS . 'config' . DS . $config_file . '.php')) {
        $_ = array();
        include ROOT . DS . 'config' . DS . $config_file . '.php';
        if (array_key_exists($config_key, $_)) {
            return $_[$config_key];
        } else {
            error("no_config_key", $config_key);
        }
    } else {
        error("no_config_file", $config_file);
    }

}


/**
 *
 * @param type $format
 * @return type
 */
function format($format) {
    $args = func_get_args();
    $format = array_shift($args);

    preg_match_all('/(?=\{)\{(\d+)\}(?!\})/', $format, $matches, PREG_OFFSET_CAPTURE);
    $offset = 0;
    foreach ($matches[1] as $data) {
        $i = $data[0];
        $format = substr_replace($format, @$args[$i], $offset + $data[1] - 1, 2 + strlen($i));
        $offset += strlen(@$args[$i]) - 2 - strlen($i);
    }

    return $format;
}


function error($msg, $args = null)
{

    $e = new E();
    echo $e->call($msg, $args) . '<br>';
    exit;

}

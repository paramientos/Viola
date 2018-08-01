<?php


if (!class_exists("E")) {

    class E
    {

        private $e = array();

        function __construct()
        {
            $this->e = array(
                'no_route' => 'Route bulunamadı',
                'no_config_file' => 'Config {0} dosyası bulunamadı',
                'no_config_key' => 'Config {0} anahtarı bulunamadı',
                'no_helper_file' => '{0} Helper dosyası bulunamadı',
            );
        }

        function call($e, $args)
        {
            if (array_key_exists($e, $this->e)) {
                return str_replace('{0}', '{' . $args[0] . '}', $this->e[$e]);
            } else {
                echo $e;
            }
        }


        public function __call($method, $args)
        {
            return $this->call($method, $args);
        }


    }


}



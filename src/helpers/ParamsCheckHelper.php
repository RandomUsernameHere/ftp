<?php
    /**
     * Created by PhpStorm.
     * Date: 10.02.17
     * Time: 2:09
     */
    namespace web136\ftp\helpers;

    class ParamsCheckHelper
    {

        /**
         * Метод бросает исключение в случае, если передан пустой адрес сервера
         * @param string $host
         *
         * @return string
         * @throws \InvalidArgumentException
         */
        public static function checkHost ($host)
        {
            $host = strval($host);
            if (empty($host)) {
                throw new \InvalidArgumentException('Некорректный параметр $host');
            }
            else{
                return $host;
            }
        }

        /**
         * @param $port
         *
         * @return int
         * @throws \UnexpectedValueException
         */
        public static function checkPort ($port){
            $port = intval($port);

            if($port < 0){
                throw new \UnexpectedValueException('$port должен быть целым числом неотрицательным больше 0');
            }
            else{
                return $port;
            }
        }
    }
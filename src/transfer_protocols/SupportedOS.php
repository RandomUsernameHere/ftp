<?php
    /**
     * Created by PhpStorm.
     * Date: 12.02.17
     * Time: 18:53
     */
    namespace web136\ftp\transfer_protocols;

    /**
     * Class SupportedOS
     * Дело в том, что список файлов, которые возвращает ftp_rawlist() зависит от ОС ftp-сервера
     * Я пока не заморачивался с разными системами, поэтому спсисок короткий
     *
     * @package web136\ftp\transfer_protocols
     */
    class SupportedOS
    {

        /**
         * Возвращает массив поддерживаемых ОС
         * @return array
         */
        public static function getList(){
            return ['UNIX'];
        }
    }
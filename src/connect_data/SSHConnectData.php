<?php
    /**
     * Created by PhpStorm.
     * Date: 12.02.17
     * Time: 23:00
     */
    namespace web136\ftp\connect_data;

    /**
     * Interface SSHConnectData
     *
     * @package web136\ftp\connect_data
     */
    interface SSHConnectData extends ConnectData
    {

        /**
         * @return string
         */
        public function getPublicKey();

        /**
         * @return string
         */
        public function getPrivateKey();

        /**
         * @return string
         */
        public function getKeyPassphrase();
    }
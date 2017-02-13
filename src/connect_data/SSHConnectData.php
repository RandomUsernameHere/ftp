<?php
    /**
     * Created by PhpStorm.
     * Date: 12.02.17
     * Time: 23:00
     */
    namespace web136\ftp\connect_data;

    interface SSHConnectData extends ConnectData
    {
        public function getPublicKey();
        public function getPrivateKey();
        public function getKeyPassphrase();
    }
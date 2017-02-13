<?php
    /**
     * Created by PhpStorm.
     * Date: 12.02.17
     * Time: 15:54
     */
    namespace web136\ftp\connect_data;

    interface ConnectData
    {
        public function getHost();
        public function getLogin();
        public function getPassword();
        public function getPort();
        public function getTimeout();
    }
<?php
    /**
     * Created by PhpStorm.
     * Date: 12.02.17
     * Time: 15:54
     */
    namespace web136\ftp\connect_data;

    /**
     * Interface ConnectData
     *
     * @package web136\ftp\connect_data
     */
    interface ConnectData
    {

        /**
         * @return string
         */
        public function getHost ();

        /**
         * @return string
         */
        public function getLogin ();

        /**
         * @return string
         */
        public function getPassword ();

        /**
         * @return integer
         */
        public function getPort ();

        /**
         * @return integer
         */
        public function getTimeout ();

        /**
         * @return array
         */
        public function getConnectData ();
    }
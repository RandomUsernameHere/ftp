<?php
    /**
     * Created by PhpStorm.
     * Date: 10.02.17
     * Time: 1:29
     */
    namespace web136\ftp\transfer_protocols;

    use web136\ftp\connect_data\ConnectData;

    abstract class AbstractTransferProtocol implements FileTransferInterface
    {

        protected $connection;

        /**@var ConnectData $connectData*/
        protected $connectData = false;

        public function __destruct ()
        {
            $this->close();
        }

        abstract protected function connect();
        abstract protected function login();

        abstract public function cd($address = '.');
        abstract public function download($file);
        abstract public function close();
        abstract public function pwd();
        abstract public function upload($file);
        abstract public function exec($comand);
    }
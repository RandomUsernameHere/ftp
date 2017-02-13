<?php
    /**
     * Created by PhpStorm.
     * Date: 13.02.17
     * Time: 4:31
     */
    namespace web136\ftp\transfer_protocols\tools;

    class SFTPDirectoryTool
    {

        protected $connection;
        protected $currentDirecctory;

        public function __construct ($connection)
        {
            if(!is_resource($connection)){
                throw new \Exception('Для работы нужно соединение SFTP');
            }
            else{
                $this->connection = $connection;
            }

            $this->setCurrentDirectory('.');
        }

        /**
         * @param mixed $currentDirecctory
         */
        protected function setCurrentDirectory ($path)
        {
            if(!$this->currentDirecctory){
                $path = '.';
            }

            $this->currentDirecctory = ssh2_sftp_realpath($this->connection, $path);
        }

        /**
         * @return mixed
         */
        public function getCurrentDirectory ()
        {

            return $this->currentDirecctory;
        }

        public function cd($path){
            if(ssh2_sftp_realpath($this->connection, $path)){
                $this->setCurrentDirectory($path);
            }
        }

    }
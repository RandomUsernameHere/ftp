<?php
    /**
     * Created by PhpStorm.
     * Date: 13.02.17
     * Time: 4:31
     */
    namespace web136\ftp\transfer_protocols\tools;

    /**
     * Class SFTPDirectoryTool
     * У меня не получилось придумать способа следуить за текущей директорией лучше;
     *
     * @package web136\ftp\transfer_protocols\tools
     */
    class SFTPDirectoryTool
    {

        /**
         * @var resource ресурс sftp-подключение
         */
        protected $connection;
        /**
         * @var string текущая директория
         */
        protected $currentDirectory;

        /**
         * SFTPDirectoryTool constructor.
         *
         * @param $connection
         *
         * @throws \Exception в случае если $connection не ресурс
         */
        public function __construct ($connection)
        {

            if (!is_resource($connection)) {
                throw new \Exception('Для работы нужно соединение SFTP');
            }
            else {
                $this->connection = $connection;
            }
            $this->setCurrentDirectory('.');
        }

        /**
         * Обновляет текущую директорию
         * @param string $path
         */
        protected function setCurrentDirectory ($path)
        {

            if (!$this->currentDirectory) {
                $path = '.';
            }
            $this->currentDirectory = ssh2_sftp_realpath($this->connection, $path);
        }

        /**
         * @return string
         */
        public function getCurrentDirectory ()
        {
            return $this->currentDirectory;
        }

        /**
         * Проверяет существует ли путь $path на сервере. Если существует, пишет его в текущую директорию
         * @param string $path
         */
        public function cd ($path)
        {

            if (ssh2_sftp_realpath($this->connection, $path)) {
                $this->setCurrentDirectory($path);
            }
        }

    }
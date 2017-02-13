<?php
    /**
     * Created by PhpStorm.
     * Date: 09.02.17
     * Time: 1:02
     */
    namespace web136\ftp\transfer_protocols;

    /**
     * Interface FileTransferInterface
     *
     * @package web136\ftp\transfer_protocols
     */
    interface FileTransferInterface
    {

        /**
         * Смена текущей директории
         * @param string $address
         *
         * @return AbstractTransferProtocol
         */
        public function cd($address = '.');

        /**
         * Загрузка файла с удаленного сервера
         * @param string $file адрес файла
         *
         * @return AbstractTransferProtocol
         */
        public function download($file);

        /**
         * закрывает соединение, освобождает ресурсы
         * @return mixed
         */
        public function close();

        /**
         * Возвращает путь к текущей рабочей директории
         * @return string
         */
        public function pwd();

        /**
         * Загружает файл на сервер
         * @param string $file
         *
         * @return mixed
         */
        public function upload($file);

        /**
         * Выполняет команду на сервере
         * @param $command
         *
         * @return mixed
         */
        public function exec($command);
    }


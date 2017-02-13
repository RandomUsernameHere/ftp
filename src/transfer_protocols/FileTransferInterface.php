<?php
    /**
     * Created by PhpStorm.
     * Date: 09.02.17
     * Time: 1:02
     */
    namespace web136\ftp\transfer_protocols;

    interface FileTransferInterface
    {
        public function cd($address = '.');
        public function download($file);
        public function close();
        public function pwd();
        public function upload($file);
        public function exec($command);
    }


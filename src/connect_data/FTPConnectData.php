<?php
    /**
     * Created by PhpStorm.
     * Date: 12.02.17
     * Time: 15:56
     */
    namespace web136\ftp\connect_data;

    use web136\ftp\helpers\ParamsCheckHelper;

    class FTPConnectData implements ConnectData
    {
        protected $connectData = [
            'HOST' => 'localhost',
            'PORT' => 0,
            'LOGIN' => 'anonymous',
            'PASSWORD' => '',
            'TIMEOUT' => 90
        ];

        public function __construct ($connectData = [
            'HOST' => 'localhost',
            'PORT' => 0,
            'LOGIN' => 'anonymous',
            'PASSWORD' => '',
            'TIMEOUT' => 90
        ])
        {
            if(!is_array($connectData)){
                throw new \InvalidArgumentException('connectData must be an array');
            }

            $connectData['HOST'] = ParamsCheckHelper::checkHost($connectData['HOST']);

            foreach ($this->connectData as $index => &$value){
                if(isset($connectData[$index])){
                    $value = $connectData[$index];
                }
            }

            $this->connectData['PORT'] = ParamsCheckHelper::checkPort($this->connectData['PORT']);
        }

        public function getLogin ()
        {
           return $this->connectData['LOGIN'];
        }

        public function getPassword ()
        {
            return $this->connectData['PASSWORD'];
        }

        public function getPort ()
        {
            return $this->connectData['PORT'];
        }

        public function getTimeout ()
        {
            return $this->connectData['TIMEOUT'];
        }

        public function getHost ()
        {
            return $this->connectData['HOST'];
        }
    }
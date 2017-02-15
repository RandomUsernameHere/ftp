<?php
    /**
     * Created by PhpStorm.
     * Date: 12.02.17
     * Time: 15:56
     */
    namespace web136\ftp\connect_data;

    use web136\ftp\helpers\ParamsCheckHelper;

    /**
     * Class FTPConnectData
     *
     * @package web136\ftp\connect_data
     */
    class FTPConnectData implements ConnectData
    {

        /**
         * @var array
         */
        protected $connectData = [
            'HOST' => 'localhost',
            'PORT' => 0,
            'LOGIN' => 'anonymous',
            'PASSWORD' => '',
            'TIMEOUT' => 90
        ];

        /**
         * FTPConnectData constructor.
         *
         * @param array $connectData
         */
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
                if(isset($connectData[$index]) && !empty($connectData[$index])){
                    $value = $connectData[$index];
                }
            }

            $this->connectData['PORT'] = ParamsCheckHelper::checkPort($this->connectData['PORT']);
        }

        /**
         * @return mixed
         */
        public function getLogin ()
        {
           return $this->connectData['LOGIN'];
        }

        /**
         * @return mixed
         */
        public function getPassword ()
        {
            return $this->connectData['PASSWORD'];
        }

        /**
         * @return mixed
         */
        public function getPort ()
        {
            return $this->connectData['PORT'];
        }

        /**
         * @return mixed
         */
        public function getTimeout ()
        {
            return $this->connectData['TIMEOUT'];
        }

        /**
         * @return mixed
         */
        public function getHost ()
        {
            return $this->connectData['HOST'];
        }

        /**
         * @return array
         */
        public function getConnectData (): array
        {
            return $this->connectData;
        }
    }
<?php
    /**
     * Created by PhpStorm.
     * Date: 12.02.17
     * Time: 22:59
     */
    namespace web136\ftp\connect_data;

    use web136\ftp\helpers\ParamsCheckHelper;

    class SFTPConnectData implements SSHConnectData
    {

        protected $connectData = [
            'HOST'                => 'localhost',
            'PORT'                => 22,
            'LOGIN'               => 'anonymous',
            'PASSWORD'            => '',
            'TIMEOUT'             => 90,
            'AUTH_TYPE'           => false,
            'PUBKEY_FILE'         => false,
            'PRIVATE_KEY_FILE'    => false,
            'KEY_FILE_PASSPHRASE' => ''
        ];

        public function __construct (
            $connectData = [
                'HOST'                => 'localhost',
                'PORT'                => 22,
                'LOGIN'               => 'anonymous',
                'PASSWORD'            => '',
                'TIMEOUT'             => 90,
                'AUTH_TYPE'           => false,
                'PUBKEY_FILE'         => false,
                'PRIVATE_KEY_FILE'    => false,
                'KEY_FILE_PASSPHRASE' => ''
            ]
        )
        {

            if (!is_array($connectData)) {
                throw new \InvalidArgumentException('connectData must be an array');
            }
            $connectData['HOST'] = ParamsCheckHelper::checkHost($connectData['HOST']);
            switch ($connectData['AUTH_TYPE']) {
                case SSHConnectTypes::FILE:
                    unset($this->connectData['PASSWORD']);
                break;
                case SSHConnectTypes::PASSWORD:
                    unset($this->connectData['PUBKEY_FILE']);
                    unset($this->connectData['PRIVATE_KEY_FILE']);
                    unset($this->connectData['KEY_FILE_PASSPHRASE']);
                break;
                default:
                    throw new \UnexpectedValueException(
                        'Тип подключения - обязательный параметр и должен быть описан одной из констант класса SSHConnectTypes'
                    );
                break;
            }
            foreach ($this->connectData as $index => &$value) {
                if (isset($connectData[$index])) {
                    $value = $connectData[$index];
                }
            }

            $this->connectData['PORT'] = ParamsCheckHelper::checkPort($this->connectData['PORT']);


            if ($this->getAuthType() === SSHConnectTypes::FILE) {
                $this->checkFile($this->getPrivateKey(), 'private');
                $this->checkFile($this->getPublicKey(), 'public');
            }
        }

        protected function checkFile ($file, $type = '')
        {

            switch ($type) {
                case 'public':
                    $additionalText = 'публичного кулюча';
                break;
                case 'private':
                    $additionalText = 'привного ключа';
                break;
                default:
                    $additionalText = '';
                break;
            }
            if (!file_exists($file)) {
                throw new \Exception("Файл {$additionalText} не существует");
            }
            if (!is_file($file)) {
                throw new \Exception("Адрес {$file} не является файлом");
            }
            if (!is_readable($file)) {
                throw new \Exception("Файл {$additionalText} не доступен для чтения");
            }
        }

        public function getAuthType ()
        {

            return $this->connectData['AUTH_TYPE'];
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

        public function getPublicKey ()
        {

            if ($this->getAuthType() === SSHConnectTypes::FILE) {
                return $this->connectData['PUBKEY_FILE'];
            }
            else {
                return false;
            }
        }

        public function getPrivateKey ()
        {

            if ($this->getAuthType() === SSHConnectTypes::FILE) {
                return $this->connectData['PRIVATE_KEY_FILE'];
            }
            else {
                return false;
            }
        }

        public function getKeyPassphrase ()
        {

            return $this->connectData['KEY_FILE_PASSPHRASE'];
        }

    }
<?php
    /**
     * Created by PhpStorm.
     * Date: 10.02.17
     * Time: 1:49
     */
    namespace web136\ftp\transfer_protocols;

    use web136\ftp\connect_data\SFTPConnectData;
    use web136\ftp\connect_data\SSHConnectTypes;
    use web136\ftp\transfer_protocols\tools\SFTPDirectoryTool;

    /**
     * Class SFTP
     *
     * @package web136\ftp\transfer_protocols
     */
    class SFTP extends AbstractTransferProtocol
    {

        /**
         * @var resource соединение ssh
         */
        protected $ssh_connection;

        /**
         * @var \web136\ftp\transfer_protocols\tools\SFTPDirectoryTool
         */
        protected $directoryTools;

        /**
         * SFTP constructor.
         *
         * @param $connectData
         *
         * @throws \Exception
         */
        public function __construct ($connectData)
        {

            $this->connectData = new SFTPConnectData($connectData);
            $this->connect();
            $this->login();
            $this->setSFTPConnection();
            $this->directoryTools = new SFTPDirectoryTool($this->connection);
        }

        /**
         * @throws \Exception
         */
        protected function connect ()
        {

            $connection = ssh2_connect(
                $this->connectData->getHost(),
                $this->connectData->getPort()
            );
            if ($connection) {
                $this->ssh_connection = $connection;
            }
            else {
                throw new \Exception('Не удалось соединиться с сервером ' . $this->connectData->getHost());
            }
        }

        /**
         * @throws \Exception
         */
        protected function login ()
        {

            switch ($this->connectData->getAuthType()) {
                case SSHConnectTypes::FILE:
                    $loginResult = ssh2_auth_pubkey_file(
                        $this->ssh_connection,
                        $this->connectData->getLogin(),
                        $this->connectData->getPublicKey(),
                        $this->connectData->getPrivateKey(),
                        $this->connectData->getKeyPassphrase()
                    );
                break;
                case SSHConnectTypes::PASSWORD:
                    $loginResult = ssh2_auth_password(
                        $this->ssh_connection,
                        $this->connectData->getLogin(),
                        $this->connectData->getPassword()
                    );
                break;
                default:
                    throw new \UnexpectedValueException(
                        'Тип подключения должен быть описан одной из констант класса SSHConnectTypes'
                    );
                break;
            }
            if (!$loginResult) {
                $this->close();
                throw new \Exception('Не удалось подключиться к серверу. Проверьте логин и пароль');
            }

        }

        /**
         * Выделяет подсистему sftp
         */
        protected function setSFTPConnection ()
        {

            $this->connection = ssh2_sftp($this->ssh_connection);
        }

        /**
         * @param string $address
         *
         * @return $this
         */
        public function cd ($address = '.')
        {

            $this->directoryTools->cd($address);

            return $this;
        }

        /**
         * @param string $file имя файла на сервере (именно файла! Файл будет взят из текущего пути)
         * @param string $path путь для сохранения файла
         *
         * @return $this
         * @throws \Exception
         */
        public function download ($file, $path = '')
        {

            if (!ssh2_sftp_realpath($this->connection, $file)) {
                $file = $this->pwd() . '/' . $file;
            }
            if (!ssh2_sftp_realpath($this->connection, $file)) {
                throw new \Exception('Файл на сервере не найден');
            }
            $dirPath = dirname($path);
            if (!realpath($dirPath)) {
                throw new \Exception('Директория назначения не существует');
            }
            if (!is_writable($dirPath)) {
                throw new \Exception("Директория назначения недоступна для записи");
            }
            $result = ssh2_scp_recv($this->ssh_connection, $file, $path);
            if (!$result) {
                throw new \Exception('Ошибка при созранении файла');
            }
            else {
                return $this;
            }
        }

        /**
         * @throws \Exception
         */
        public function close ()
        {

            $this->exec('exit;');
            $this->connection = null;
            $this->ssh_connection = null;
        }

        /**
         * @return string
         */
        public function pwd ()
        {

            return $this->directoryTools->getCurrentDirectory();
        }

        /**
         * @param string $file
         * @param string $path Необязательный. Если не задать, будет использован текущий путь
         *
         * @return $this
         * @throws \Exception
         */
        public function upload ($file, $path = '')
        {

            if (!$path) {
                $path = $this->pwd();
            }
            if (!file_exists($file)) {
                throw new \Exception("Файла '{$file}' не существует");
            }
            if (!is_file($file)) {
                throw new \Exception("Путь '{$file}' не ведет к файлу");
            }
            if (!is_readable($file)) {
                throw new \Exception("Нет прав на чтение {$file}");
            }
            $fileName = basename($file);
            $result = ssh2_scp_send($this->ssh_connection, $file, $path . '/' . $fileName, 0775);
            if (!$result) {
                throw new \Exception('Не удалось загрузить файл ' . $fileName);
            }
            else {
                return $this;
            }
        }

        /**
         * @param $comand
         *
         * @return string
         * @throws \Exception
         */
        public function exec ($comand)
        {

            $stream = ssh2_exec($this->ssh_connection, $comand . PHP_EOL);
            if (!$stream) {
                throw new \Exception('SSH command failed');
            }
            stream_set_blocking($stream, true);
            $data = "";
            while ($buf = fread($stream, 4096)) {
                $data .= $buf;
            }
            fclose($stream);

            return $data;
        }

    }
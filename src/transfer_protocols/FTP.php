<?php
    /**
     * Created by PhpStorm.
     * Date: 10.02.17
     * Time: 1:48
     */
    namespace web136\ftp\transfer_protocols;

    use web136\ftp\connect_data\FTPConnectData;

    class FTP extends AbstractTransferProtocol
    {

        public function __construct ($connectData)
        {
            $this->connectData = new FTPConnectData($connectData);
            $this->connect();
            $this->login();
            if(!in_array(strtoupper(ftp_systype($this->connection)), SupportedOS::getList())){
                $this->close();
                throw new \Exception('FTP сервер хостится на неподдерживаемой системе');
            }
        }

        public function cd ($address = '.')
        {
            $result = ftp_chdir($this->connection, $address);

            if($result){
                return $this;
            }
            else{
                throw new \Exception('Ошибка при смене директории');
            }
        }

        public function download ($file)
        {
            if(!empty(strval($file))){
                $result = ftp_get($this->connection, '/home/jaman/www/test-ftp.net/web/downloads/' . $file, $file,
                                  FTP_BINARY);

                if(!$result){
                    throw  new \Exception('Не удалось загрузить файл');
                }
            }

            return $this;

        }

        public function close ()
        {
            ftp_close($this->connection);
        }

        public function pwd ()
        {
            return ftp_pwd($this->connection);
        }

        public function upload ($file)
        {
            if(!file_exists($file)){
                throw new \Exception("Файла '{$file}' не существует");
            }

            if(!is_file($file)){
                throw new \Exception("Путь '{$file}' не ведет к файлу");
            }

            if(!is_readable($file)){
                throw new \Exception("Нет прав на чтение {$file}");
            }

            $fileName = basename($file);

            $result = ftp_put($this->connection, $fileName, $file, FTP_BINARY);

            if(!$result){
                throw new \Exception('Не удалось загрузить файл '.$fileName);
            }
            else{
                return $this;
            }
        }

        public function exec ($command)
        {
            $result = ftp_exec($this->connection, $command);

            if(!$result){
                throw new \Exception('Команда "'.$command.'" не выполнена');
            }

            return $this;
        }

        protected function connect ()
        {
            $connection = ftp_connect(
                $this->connectData->getHost(), $this->connectData->getPort(), $this->connectData->getTimeout()
            );

            if($connection){
                $this->connection = $connection;
            }
            else{
                throw new \Exception('Не удалось соединиться с сервером '. $this->connectData->getHost());
            }
        }

        protected function login ()
        {
            $loginResult = ftp_login(
                $this->connection,
                $this->connectData->getLogin(),
                $this->connectData->getPassword()
            );

            if(!$loginResult){
                $this->close();
                throw new \Exception('Не удалось подключиться к серверу. Проверьте логин и пароль');
            }
        }

        public function currentDirectoryList(){
            return $this->dirList($this->pwd());
        }

        public function dirList($directory){

            $result = false;

            $rawList = ftp_rawlist($this->connection, $directory);

            if(is_array($rawList)){

                foreach ($rawList as $item){

                    $chunks = preg_split("/\s+/", $item);

                    $result[] = [
                        'IS_DIR' => $chunks[0][0] === 'd',
                        'RIGHTS' => substr($chunks[0], 1),
                        'USER' => $chunks[2],
                        'GROUP' => $chunks[3],
                        'DATE' => "{$chunks[5]} {$chunks[6]} {$chunks[7]}",
                        'NAME' => implode(' ', array_slice($chunks, 8))
                    ];
                }
            }

            return $result;
        }
    }
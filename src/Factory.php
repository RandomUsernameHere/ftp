<?php
    namespace web136\ftp;
    use web136\ftp\transfer_protocols as protocols;

    /**
     * Class Factory
     * Основная задача - создание объекта, описывающего соединение
     *
     * @package web136\ftp
     */
    class Factory
    {

        /**
         * Возвращает обект, описывающий соединение
         * @param string $host адрес сервера
         * @param integer|null $port порт подключения(для использования порта по умолчанию нужно передать null)
         * @param bool   $user пользователь
         * @param bool   $password пароль
         * @param bool   $type тип подключения. Возможные типы подключения есть в web136\ftp\EnumFileTransferProtocols
         *
         * @return \web136\ftp\transfer_protocols\FTP|\web136\ftp\transfer_protocols\SFTP объект, описывающий соединение
         * @throws \Exception если указан некорректный тип соединения
         */
        public static  function getConnection ($type = false, $connectData)
        {

            switch ($type) {
                case protocols\EnumFileTransferProtocols::PROTOCOL_FTP:
                    return new protocols\FTP($connectData);
                break;
                case protocols\EnumFileTransferProtocols::PROTOCOL_SFTP:
                    return new protocols\SFTP($connectData);
                break;
                default:
                    throw new \Exception('Неподдерживаемый протокол');
                break;
            }
        }

    }
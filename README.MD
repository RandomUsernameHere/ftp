#Пакет для работы с ftp и sftp

Создан в рамках тестового задания.
 
 ##Использование
 
 ```php 
 use web136\ftp;
 
 try{
    $connect = Factory::getConnection($type, $connectionData);
 }
 catch(\Exception $e){
       echo $e->getMessage();
 }
 
 ```
 
 где ``` $type ``` - тип соединения: 
 ```EnumFileTransferProtocols::PROTOCOL_FTP``` или ```EnumFileTransferProtocols::PROTOCOL_SFTP:``` , а ```$connectionData``` - массив, который содержит параметры соединения.
 Общие параметры для обоих типов:
 ```php
    $connectData = [
                'HOST' => 'localhost', //хост
                'PORT' => 0, //порт (для SFTP порт по умолчанию 22) 
                'LOGIN' => 'anonymous', //пользователь
                'PASSWORD' => '', //пароль
                'TIMEOUT' => 90 //таймаут соединения
            ];
 ```
 Спецефические параметры для SFTP:
 ```php 
 [
    'AUTH_TYPE'  //тип авторизации (одна из констант SSHConnectTypes)
    'PUBKEY_FILE' //путь до публичного ключа
     'PRIVATE_KEY_FILE' //путь до приватного ключа
     'KEY_FILE_PASSPHRASE' //пароль к файлам ключей
 ]
 ```
 
 Файлы ключей обязательны для ```SSHConnectTypes::File```.
 
 *Внимание!* работа кода для типа ```SSHConnectTypes::File``` не проверялась! К сожалению, я не умею настраивать доступ ssh  по ключу и под рукой не оказалось серверов, к которым такой доступ настроен.
 
 В случае успеха ```Factory::getConnection()``` возвращает объект класса ```\web136\ftp\transfer_protocols\FTP``` или ```\web136\ftp\transfer_protocols\SFTP```
<?php
class DBFactory
{
    public static function getMysqlConnexionWithPDO()
    {
        $db = new PDO('mysql:host=localhost;dbname=ocr_poo', 'root', 'toto');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $db;
    }

    public static function getMysqlConnexionWithMySQLi()
    {
        return new MySQLi('localhost', 'root', 'toto', 'ocr_poo');
    }
}

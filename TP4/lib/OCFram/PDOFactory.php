<?php

namespace OCFram;

class PDOFactory
{
    public static function getMysqlConnexion()
    {
        $db = new \PDO('mysql:host=localhost;dbname=ocr_poo', 'root', 'toto');
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $db;
    }
}

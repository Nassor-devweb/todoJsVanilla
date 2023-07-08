<?php

class Connexion
{
    private const DNS = 'mysql:host=localhost;dbname=todolist';
    private const USER = 'root';
    private const PSWD = 'Liminose123';
    private static $connect =  null;

    static function connectDb()
    {
        if (is_null(self::$connect)) {
            try {
                self::$connect = new PDO(self::DNS, self::USER, self::PSWD, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
                self::$connect->exec('SET CHARACTER SET UTF8');
            } catch (PDOException $err) {
                echo $err->getMessage();
            }
        }
        return self::$connect;
    }
}

<?php

require_once 'config.m.php';


class DB {
    //put your code here
    private static $db = null;
    
    // Konstruktor privat machen, damit er nicht aufgerufen werden kann
    private function __construct() {
        ;
    }

    public static function getDB() {
       
       if (self::$db == NULL){
        try{
            self::$db = new PDO("mysql:host=" . D_HOST . ";dbname=" . D_NAME . ";charset=utf8", D_USER, D_PASSWORD);
            //self::$db = new PDO("mysql:host=" . 'localhost' . ";dbname=" . 'db_421679_10' . ";charset=utf8", 'root', '');
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }

       }
       return self::$db;
    }
}
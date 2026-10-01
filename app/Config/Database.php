<?php
namespace App\Config;
use PDO;
final class Database { private static ?PDO $pdo=null; public static function get(): PDO { if(self::$pdo) return self::$pdo; $dsn='mysql:host='.($_ENV['DB_HOST']??'127.0.0.1').';port='.($_ENV['DB_PORT']??'3306').';dbname='.($_ENV['DB_NAME']??'imei_saas').';charset=utf8mb4'; return self::$pdo=new PDO($dsn,$_ENV['DB_USER']??'root',$_ENV['DB_PASS']??'',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]); } }

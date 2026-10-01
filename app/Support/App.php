<?php
namespace App\Support;use App\Config\Database;
final class App{
 public static function db(){return Database::get();}
 public static function json(array $d,int $s=200):never{http_response_code($s);header('Content-Type: application/json; charset=utf-8');echo json_encode($d,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);exit;}
 public static function input():array{$r=file_get_contents('php://input');$j=json_decode($r?:'',true);return is_array($j)?$j:$_POST;}
 public static function setting(string $k,$default=null){$q=self::db()->prepare('SELECT value FROM settings WHERE `key`=?');$q->execute([$k]);$v=$q->fetchColumn();return $v===false?$default:$v;}
 public static function set(string $k,string $v):void{self::db()->prepare('INSERT INTO settings(`key`,value) VALUES(?,?) ON DUPLICATE KEY UPDATE value=VALUES(value)')->execute([$k,$v]);}
 public static function user():?array{if(empty($_SESSION['uid']))return null;$q=self::db()->prepare('SELECT u.*,l.code level_code FROM users u LEFT JOIN user_levels l ON l.id=u.level_id WHERE u.id=?');$q->execute([$_SESSION['uid']]);return $q->fetch()?:null;}
 public static function requireAuth():array{$u=self::user();if(!$u)self::json(['ok'=>false,'message'=>'Unauthorized'],401);return $u;}
 public static function requireAdmin():array{$u=self::requireAuth();if($u['role']!=='admin')self::json(['ok'=>false,'message'=>'Forbidden'],403);return $u;}
 public static function apiUser():array{$a=$_SERVER['HTTP_AUTHORIZATION']??'';$k='';if(preg_match('/^Bearer\s+(.+)$/i',$a,$m))$k=trim($m[1]);$k=$k?:trim($_SERVER['HTTP_X_API_KEY']??'');if(!$k)self::json(['ok'=>false,'message'=>'API key required'],401);$q=self::db()->prepare('SELECT u.*,l.code level_code,a.id api_key_id FROM api_keys a JOIN users u ON u.id=a.user_id LEFT JOIN user_levels l ON l.id=u.level_id WHERE a.key_hash=? AND a.is_active=1 AND u.status="active"');$q->execute([hash('sha256',$k)]);$u=$q->fetch();if(!$u)self::json(['ok'=>false,'message'=>'Invalid API key'],401);return $u;}
 public static function csrf():string{if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(24));return $_SESSION['csrf'];}
 public static function verifyCsrf():void{$t=$_SERVER['HTTP_X_CSRF_TOKEN']??$_POST['_csrf']??'';if(!hash_equals($_SESSION['csrf']??'',(string)$t))self::json(['ok'=>false,'message'=>'CSRF token invalid'],419);}
}
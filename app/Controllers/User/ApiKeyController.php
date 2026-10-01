<?php
namespace App\Controllers\User;use App\Support\App;
final class ApiKeyController{
 public function create():never{$u=App::requireAuth();$in=App::input();$label=trim($in['label']??'Default API');$key='cs_'.bin2hex(random_bytes(24));App::db()->prepare('INSERT INTO api_keys(user_id,key_hash,label) VALUES(?,?,?)')->execute([$u['id'],hash('sha256',$key),substr($label,0,100)]);App::json(['ok'=>true,'key'=>$key,'message'=>'Simpan API key ini. Key tidak dapat ditampilkan lagi.']);}
 public function revoke():never{$u=App::requireAuth();$id=(int)(App::input()['id']??0);App::db()->prepare('UPDATE api_keys SET is_active=0 WHERE id=? AND user_id=?')->execute([$id,$u['id']]);App::json(['ok'=>true]);}
}
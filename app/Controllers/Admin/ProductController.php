<?php
namespace App\Controllers\Admin;use App\Support\App;use App\Services\Provider\CeirGoProvider;
final class ProductController{
 public function save():never{
  App::requireAdmin();$in=App::input();$db=App::db();$id=(int)($in['id']??0);
  $name=trim((string)($in['name']??''));$code=strtoupper(trim((string)($in['code']??'')));
  if($name===''||$code==='')App::json(['ok'=>false,'message'=>'Nama dan code produk wajib diisi'],422);
  if(!preg_match('/^[A-Z0-9_-]+$/',$code))App::json(['ok'=>false,'message'=>'Code hanya boleh huruf, angka, _ atau -'],422);
  $provider=in_array($in['provider_type']??'manual',['manual','ceirgo'],true)?$in['provider_type']:'manual';
  $data=[$name,$code,trim((string)($in['description']??'')),$provider,trim((string)($in['provider_service_id']??''))?:null,trim((string)($in['provider_service_code']??''))?:null,max(0,(float)($in['provider_cost']??0)),trim((string)($in['whatsapp_group_id']??''))?:null,!empty($in['ceir_check_enabled'])?1:0,!empty($in['auto_refund'])?1:0,!empty($in['is_active'])?1:0,trim((string)($in['processing_time']??''))?:null,(int)($in['sort_order']??0)];
  try{$db->beginTransaction();
   if($id){$data[]=$id;$db->prepare('UPDATE products SET name=?,code=?,description=?,provider_type=?,provider_service_id=?,provider_service_code=?,provider_cost=?,whatsapp_group_id=?,ceir_check_enabled=?,auto_refund=?,is_active=?,processing_time=?,sort_order=? WHERE id=?')->execute($data);}
   else{$db->prepare('INSERT INTO products(name,code,description,provider_type,provider_service_id,provider_service_code,provider_cost,whatsapp_group_id,ceir_check_enabled,auto_refund,is_active,processing_time,sort_order) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)')->execute($data);$id=(int)$db->lastInsertId();}
   $levels=$db->query('SELECT id,code FROM user_levels')->fetchAll();
   foreach($levels as $level){$price=max(0,(float)(($in['prices']??[])[$level['code']]??0));$db->prepare('INSERT INTO product_prices(product_id,level_id,price) VALUES(?,?,?) ON DUPLICATE KEY UPDATE price=VALUES(price)')->execute([$id,$level['id'],$price]);}
   $db->commit();App::json(['ok'=>true,'id'=>$id]);
  }catch(\PDOException $e){if($db->inTransaction())$db->rollBack();if(($e->errorInfo[1]??0)==1062)App::json(['ok'=>false,'message'=>'Code produk sudah digunakan'],409);throw $e;}catch(\Throwable $e){if($db->inTransaction())$db->rollBack();throw $e;}
 }
 public function importCeirGo():never{App::requireAdmin();try{$r=(new CeirGoProvider())->services();$items=$r['data']['page']['items']??$r['data']['items']??$r['data']??[];if(!is_array($items))$items=[];$db=App::db();$n=0;foreach($items as $s){if(!is_array($s)||empty($s['code']))continue;$db->prepare('INSERT INTO provider_services(provider,provider_service_id,code,name,description,provider_price,is_active,raw_json) VALUES("ceirgo",?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE provider_service_id=VALUES(provider_service_id),name=VALUES(name),description=VALUES(description),provider_price=VALUES(provider_price),is_active=VALUES(is_active),raw_json=VALUES(raw_json),updated_at=NOW()')->execute([(string)($s['id']??$s['service_id']??''),(string)$s['code'],(string)($s['name']??$s['code']),$s['description']??null,(float)($s['price']??$s['cost']??0),!isset($s['is_active'])||!empty($s['is_active'])?1:0,json_encode($s)]);$n++;}App::json(['ok'=>true,'imported'=>$n]);}catch(\Throwable $e){App::json(['ok'=>false,'message'=>$e->getMessage()],502);}}
}
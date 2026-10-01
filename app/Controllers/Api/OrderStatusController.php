<?php
namespace App\Controllers\Api;use App\Support\App;
final class OrderStatusController{public function show(string $no):never{$u=App::requireAuth();$q=App::db()->prepare('SELECT order_number,imei,status,selling_price,ceir_status,created_at,completed_at FROM orders WHERE order_number=? AND user_id=?');$q->execute([$no,$u['id']]);$r=$q->fetch();if(!$r)App::json(['ok'=>false,'message'=>'Order tidak ditemukan'],404);App::json(['ok'=>true,'order'=>$r]);}}

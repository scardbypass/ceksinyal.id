<?php
namespace App\Controllers\Api;use App\Support\App;use App\Services\OrderService;
final class OrderApiController{public function create():never{$u=App::user();if(!$u)App::json(['ok'=>false,'message'=>'Unauthorized'],401);try{$in=App::input();$o=(new OrderService())->create((int)$u['id'],(int)($in['product_id']??0),preg_replace('/\D/','',(string)($in['imei']??'')));App::json(['ok'=>true,'data'=>$o],201);}catch(\Throwable $e){App::json(['ok'=>false,'message'=>$e->getMessage()],422);}}}

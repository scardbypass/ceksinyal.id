<?php
namespace App\Controllers\Admin;use App\Support\App;use App\Services\OrderService;
final class OrderController{public function bulk():never{App::requireAdmin();$in=App::input();$ids=array_values(array_unique(array_filter(array_map('intval',(array)($in['ids']??[])))));$status=(string)($in['status']??'');if(!$ids)App::json(['ok'=>false,'message'=>'Pilih order'],422);foreach($ids as $id)(new OrderService())->setStatus($id,$status,(string)($in['reason']??''));App::json(['ok'=>true,'updated'=>count($ids)]);}}

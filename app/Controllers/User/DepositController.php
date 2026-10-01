<?php
namespace App\Controllers\User;
use App\Support\App;use App\Services\DepositService;
final class DepositController{
 public function create():never{App::verifyCsrf();$u=App::requireAuth();$in=App::input();$gateway=(string)($in['gateway']??'manual');$amount=(float)($in['amount']??0);if($amount<1000)App::json(['ok'=>false,'message'=>'Minimum deposit Rp1.000'],422);try{$r=(new DepositService())->create((int)$u['id'],$gateway,$amount);App::json(['ok'=>true,'deposit'=>$r]);}catch(\Throwable $e){App::json(['ok'=>false,'message'=>$e->getMessage()],422);}}
}

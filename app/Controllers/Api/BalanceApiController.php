<?php
namespace App\Controllers\Api;use App\Support\App;
final class BalanceApiController{public function show():never{$u=App::requireAuth();App::json(['ok'=>true,'balance'=>(float)$u['balance'],'level'=>$u['level_code']]);}}

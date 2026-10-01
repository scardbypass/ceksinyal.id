<?php
namespace App\Services; use App\Services\Provider\CeirGoProvider;
final class CeirService { public function check(string $imei):array{if(!preg_match('/^\d{15}$/',$imei))throw new \InvalidArgumentException('IMEI harus tepat 15 digit');$r=(new CeirGoProvider())->create('cek_imei',['imeis'=>[$imei]]);$b=$r['result']??[];$status='ERROR';foreach(['REGISTERED','ROAMER','UNKNOWN','ERROR'] as $s){if(in_array($imei,(array)($b[$s]??[]),true)){$status=$s;break;}}return ['status'=>$status,'raw'=>$r];}}

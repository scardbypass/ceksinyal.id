<?php
namespace App\Payments\Gateways\Jago;
use App\Payments\PaymentInterface;
final class JagoGateway implements PaymentInterface { public function create(array $d):array{return ['status'=>'pending','amount'=>$d['amount'],'expires_at'=>$d['expires_at']??null];} public function check(array $d):array{return ['status'=>'pending'];} }

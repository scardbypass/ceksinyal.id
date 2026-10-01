<?php
namespace App\Payments\Gateways\Manual;
use App\Payments\PaymentInterface;
final class ManualGateway implements PaymentInterface { public function create(array $d):array{return ['status'=>'pending','instructions'=>'Transfer lalu upload bukti pembayaran.'];} public function check(array $d):array{return ['status'=>$d['status']??'pending'];} }

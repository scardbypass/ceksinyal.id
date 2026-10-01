<?php
namespace App\Payments\Gateways\GoBiz;use App\Payments\PaymentInterface;
final class GoBizGateway implements PaymentInterface{public function create(array $d):array{$qr=(new GoBizQris())->dynamic($_ENV['GOBIZ_QR_STRING']??'',(int)$d['amount']);return ['status'=>'pending','qris'=>$qr,'expires_at'=>$d['expires_at']??null];}public function check(array $d):array{return ['status'=>'pending'];}}

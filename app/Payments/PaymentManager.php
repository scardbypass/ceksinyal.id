<?php
namespace App\Payments;use App\Payments\Gateways\Manual\ManualGateway;use App\Payments\Gateways\GoBiz\GoBizGateway;use App\Payments\Gateways\Jago\JagoGateway;
final class PaymentManager{public function gateway(string $code):PaymentInterface{return match($code){'manual'=>new ManualGateway(),'gobiz'=>new GoBizGateway(),'jago'=>new JagoGateway(),default=>throw new \InvalidArgumentException('Gateway tidak dikenal')};}}

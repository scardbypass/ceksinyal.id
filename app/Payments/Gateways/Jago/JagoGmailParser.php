<?php
namespace App\Payments\Gateways\Jago;
final class JagoGmailParser { public function parse(string $subject,string $body,string $messageId):?array{if(!preg_match('/(?:Rp\s*)?([0-9\.]+(?:,[0-9]{2})?)/i',$body,$m))return null;$amount=(float)str_replace(['.',','],['','.'],$m[1]);if($amount<=0)return null;return ['external_id'=>'gmail:'.$messageId,'amount'=>$amount,'subject'=>$subject,'body'=>$body];} }

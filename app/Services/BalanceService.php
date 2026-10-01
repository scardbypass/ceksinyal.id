<?php
namespace App\Services;use App\Config\Database;
final class BalanceService{public function debit(int $uid,float $amount,string $ref):void{$this->move($uid,-abs($amount),'order',$ref);}public function credit(int $uid,float $amount,string $type,string $ref):void{$this->move($uid,abs($amount),$type,$ref);}private function move(int $uid,float $delta,string $type,string $ref):void{$db=Database::get();$db->beginTransaction();try{WalletService::move($db,$uid,$delta,$type,$ref,$type.':'.$ref);$db->commit();}catch(\Throwable $e){if($db->inTransaction())$db->rollBack();throw $e;}}}}

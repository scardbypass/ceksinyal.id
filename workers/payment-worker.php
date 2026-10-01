<?php
require dirname(__DIR__).'/vendor/autoload.php';$dotenv=Dotenv\Dotenv::createImmutable(dirname(__DIR__));$dotenv->safeLoad();
use App\Config\Database;use App\Support\App;use App\Services\DepositService;use App\Payments\Gateways\GoBiz\GoBizJournal;
$db=Database::get();
try{$q=$db->prepare('SELECT is_active FROM payment_gateways WHERE code="gobiz"');$q->execute();if(!(bool)$q->fetchColumn())exit(0);foreach((new GoBizJournal())->fetch() as $e){$ext=(string)($e['id']??$e['reference']??'');$amount=(float)($e['amount']??0);if(!$ext||$amount<=0)continue;$q=$db->prepare('SELECT id FROM deposits WHERE gateway="gobiz" AND status="pending" AND amount=? AND expires_at>=NOW() ORDER BY id');$q->execute([$amount]);$ids=$q->fetchAll(PDO::FETCH_COLUMN);if(count($ids)!==1)continue;(new DepositService())->settle((int)$ids[0],$ext,$e);}}catch(Throwable $e){file_put_contents(dirname(__DIR__).'/storage/logs/payment-worker.log',date('c').' '.$e->getMessage().PHP_EOL,FILE_APPEND);}

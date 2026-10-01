<?php
use App\Support\App;use App\Controllers\Auth\AuthController;use App\Controllers\Api\OrderApiController;use App\Controllers\Api\ProductApiController;use App\Controllers\Api\BalanceApiController;use App\Controllers\Api\OrderStatusController;use App\Controllers\Admin\ProductController;use App\Controllers\Admin\OrderController;use App\Controllers\Admin\SettingController;use App\Controllers\Api\BotApiController;use App\Controllers\Api\DhruController;use App\Controllers\User\TicketController;use App\Controllers\User\DepositController;
$path=rtrim(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH)?:'/','/')?:'/';$method=$_SERVER['REQUEST_METHOD'];
$public=['/'=>'home/index.php','/login'=>'auth/login.php','/register'=>'auth/register.php','/docs'=>'docs/index.php'];$user=['/dashboard'=>'user/dashboard.php','/order'=>'user/order.php','/deposit'=>'user/deposit.php','/tickets'=>'tickets/index.php'];$admin=['/admin'=>'admin/dashboard.php','/admin/products'=>'admin/products.php','/admin/orders'=>'admin/orders.php','/admin/settings'=>'admin/settings.php'];
if($method==='GET'&&isset($public[$path])){require dirname(__DIR__).'/views/'.$public[$path];exit;}if($method==='GET'&&isset($user[$path])){App::requireAuth();require dirname(__DIR__).'/views/'.$user[$path];exit;}if($method==='GET'&&isset($admin[$path])){App::requireAdmin();require dirname(__DIR__).'/views/'.$admin[$path];exit;}
try{
 if($method==='POST'&&$path==='/api/auth/login'){App::verifyCsrf();(new AuthController())->login();}
 if($method==='POST'&&$path==='/api/auth/register'){App::verifyCsrf();(new AuthController())->register();}
 if($method==='GET'&&$path==='/api/v1/products')(new ProductApiController())->index();
 if($method==='GET'&&$path==='/api/v1/balance')(new BalanceApiController())->show();
 if($method==='GET'&&preg_match('#^/api/v1/orders/([^/]+)$#',$path,$m))(new OrderStatusController())->show($m[1]);
 if($method==='POST'&&in_array($path,['/api/orders','/api/v1/orders'],true))(new OrderApiController())->create();
 if($method==='POST'&&$path==='/api/deposits')(new DepositController())->create();
 if($method==='POST'&&$path==='/api/admin/products'){App::requireAdmin();App::verifyCsrf();(new ProductController())->save();}
 if($method==='POST'&&$path==='/api/admin/products/import-ceirgo'){App::requireAdmin();App::verifyCsrf();(new ProductController())->importCeirGo();}
 if($method==='POST'&&$path==='/api/admin/orders/bulk'){App::requireAdmin();App::verifyCsrf();(new OrderController())->bulk();}
 if($method==='POST'&&$path==='/api/admin/settings'){App::requireAdmin();App::verifyCsrf();(new SettingController())->save();}
 if($method==='POST'&&$path==='/api/bot/reply')(new BotApiController())->reply();
 if($method==='POST'&&$path==='/api/tickets'){App::verifyCsrf();(new TicketController())->create();}
 if($path==='/api/dhru')(new DhruController())->handle();
}catch(InvalidArgumentException $e){App::json(['ok'=>false,'message'=>$e->getMessage()],422);}catch(RuntimeException $e){App::json(['ok'=>false,'message'=>$e->getMessage()],409);}catch(Throwable $e){error_log((string)$e);App::json(['ok'=>false,'message'=>($_ENV['APP_DEBUG']??'false')==='true'?$e->getMessage():'Internal server error'],500);}http_response_code(404);echo '404';

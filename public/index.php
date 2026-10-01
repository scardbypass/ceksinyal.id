<?php
declare(strict_types=1);
$root=dirname(__DIR__);require $root.'/vendor/autoload.php';$dotenv=Dotenv\Dotenv::createImmutable($root);$dotenv->safeLoad();$secure=($_ENV['SESSION_SECURE']??'true')==='true';session_set_cookie_params(['httponly'=>true,'secure'=>$secure,'samesite'=>'Lax','path'=>'/']);session_start();header('X-Content-Type-Options: nosniff');header('X-Frame-Options: SAMEORIGIN');header('Referrer-Policy: strict-origin-when-cross-origin');require $root.'/routes/web.php';

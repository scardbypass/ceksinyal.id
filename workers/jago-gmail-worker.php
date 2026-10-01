<?php
require dirname(__DIR__).'/vendor/autoload.php';$dotenv=Dotenv\Dotenv::createImmutable(dirname(__DIR__));$dotenv->safeLoad();
if(($_ENV['JAGO_ENABLED']??'false')!=='true')exit(0);
if(!($_ENV['JAGO_GMAIL_REFRESH_TOKEN']??'')){fwrite(STDERR,"Jago Gmail OAuth belum dikonfigurasi\n");exit(2);}
echo "Jago Gmail OAuth configured. Verified transport/parser integration must be tested before auto settlement.\n";

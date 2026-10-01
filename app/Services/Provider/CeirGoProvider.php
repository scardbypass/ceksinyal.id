<?php
namespace App\Services\Provider;use App\Support\App;
final class CeirGoProvider implements ProviderInterface {
 private function request(string $path,string $method='GET',?array $body=null):array{$url=rtrim((string)App::setting('ceirgo_base_url',$_ENV['CEIRGO_BASE_URL']??'https://ceirgo.id'),'/').$path;$key=(string)App::setting('ceirgo_api_key',$_ENV['CEIRGO_API_KEY']??'');$h=['Accept: application/json','Authorization: Bearer '.$key];if($body!==null)$h[]='Content-Type: application/json';$ch=curl_init($url);curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_CUSTOMREQUEST=>$method,CURLOPT_HTTPHEADER=>$h,CURLOPT_TIMEOUT=>35,CURLOPT_CONNECTTIMEOUT=>10]);if($body!==null)curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($body));$raw=curl_exec($ch);$code=curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);$j=json_decode((string)$raw,true);if($code>=400||!is_array($j))throw new \RuntimeException('CeirGo HTTP '.$code);return $j;}
 public function services():array{return $this->request('/api/services?limit=50');}
 public function create(string $code,array $data):array{return $this->request('/api/order','POST',['code'=>$code,'data'=>$data]);}
 public function submit(array $order,array $product):array{return $this->create((string)$product['provider_service_code'],['imeis'=>[$order['imei']]]);}
 public function status(string $id):array{return $this->request('/api/order/'.rawurlencode($id).'/status');}
}

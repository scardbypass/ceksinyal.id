<?php
namespace App\Services\Provider;
final class ManualProvider implements ProviderInterface {
 public function submit(array $order,array $product): array { return ['status'=>'processing','group_id'=>$product['whatsapp_group_id']??null]; }
 public function status(string $providerOrderId): array { return ['status'=>'processing']; }
}

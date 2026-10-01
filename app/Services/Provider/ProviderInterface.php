<?php
namespace App\Services\Provider;
interface ProviderInterface { public function submit(array $order,array $product): array; public function status(string $providerOrderId): array; }

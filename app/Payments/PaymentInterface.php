<?php
namespace App\Payments;
interface PaymentInterface { public function create(array $deposit): array; public function check(array $deposit): array; }

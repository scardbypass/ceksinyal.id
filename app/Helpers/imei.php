<?php
namespace App\Helpers;
final class Imei { public static function normalize(string $value): string { return preg_replace('/\D+/','',$value) ?? ''; } public static function valid(string $value): bool { return (bool)preg_match('/^\d{15}$/',$value); } public static function requireValid(string $value): string { $v=self::normalize($value); if(!self::valid($v)) throw new \InvalidArgumentException('IMEI harus tepat 15 digit angka.'); return $v; } }

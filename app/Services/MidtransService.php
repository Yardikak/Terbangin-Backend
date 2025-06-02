<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.sanitized');
        Config::$is3ds = config('services.midtrans.3ds');
    }

    public function createTransaction(array $params)
    {
        return Snap::createTransaction($params);
    }

    public function getStatus(string $orderId)
    {
        return \Midtrans\Transaction::status($orderId);
    }
}

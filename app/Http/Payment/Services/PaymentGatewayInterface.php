<?php

declare(strict_types=1);

namespace App\Http\Payment\Services;

interface PaymentGatewayInterface
{
    public function charge(float $amount, array $paymentDetails): bool;
}

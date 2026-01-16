<?php

declare(strict_types=1);

namespace App\Http\Payment\Services;

use Illuminate\Support\Facades\Log;

final class PaypalPaymentGateway implements PaymentGatewayInterface
{
    public function charge(float $amount, array $paymentDetails): bool
    {
        Log::info("PaypalPaymentGateway -> charging -> $amount");

        return true;
    }
}

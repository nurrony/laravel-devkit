<?php

declare(strict_types=1);

namespace App\Http\Payment\Services;

use Illuminate\Support\Facades\Log;

final class StripePaymentGateway implements PaymentGatewayInterface
{
    public function charge(float $amount, array $paymentDetails): bool
    {
        Log::info("StripePaymentGateway -> charging -> $amount");

        return true;
    }
}

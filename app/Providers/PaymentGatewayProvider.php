<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Payment\Services\PaymentGatewayInterface;
use App\Http\Payment\Services\PaypalPaymentGateway;
use App\Http\Payment\Services\StripePaymentGateway;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

final class PaymentGatewayProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Log::info('calling PaymentGatewayProvider');
        $this->app->bind(PaymentGatewayInterface::class, function ($app) {
            $gateway = config('payment.default_gateway');

            return match ($gateway) {
                'stripe' => new StripePaymentGateway(),
                'paypal' => new PaypalPaymentGateway(),
                default => throw new Exception("Unsupported gateway: {$gateway}")
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

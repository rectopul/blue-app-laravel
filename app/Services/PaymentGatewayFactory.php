<?php

namespace App\Services;

use App\Models\Setting;
use App\Services\ValorionPay\ValorionPayService;
use App\Services\BitFlow\BitFlowService;

class PaymentGatewayFactory
{
    public static function create()
    {
        $settings = Setting::first();
        $activeGateway = $settings->active_gateway ?? 'valorionpay';

        return match ($activeGateway) {
            'bitflow' => app(BitFlowService::class),
            default => app(ValorionPayService::class),
        };
    }

    public static function getActiveGatewayName()
    {
        $settings = Setting::first();
        return $settings->active_gateway ?? 'valorionpay';
    }
}

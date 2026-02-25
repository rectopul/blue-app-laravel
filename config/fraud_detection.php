<?php

return [
    'risk_thresholds' => [
        'low' => env('FRAUD_RISK_LOW', 30),
        'medium' => env('FRAUD_RISK_MEDIUM', 60),
        'high' => env('FRAUD_RISK_HIGH', 80),
    ],

    'auto_block_threshold' => env('FRAUD_AUTO_BLOCK_THRESHOLD', 90),

    'withdrawal_limits' => [
        'high_risk' => 0,      // Usuários de alto risco não podem sacar
        'medium_risk' => 500,  // Limite de R$ 500
        'low_risk' => 2000,    // Limite de R$ 2000
        'no_risk' => null,     // Sem limite
    ],

    'analysis_frequency' => [
        'new_users' => 'immediate',    // Análise imediata para novos usuários
        'existing_users' => 'daily',   // Análise diária para usuários existentes
        'high_risk_users' => 'hourly', // Análise horária para usuários de alto risco
    ],

    'notification' => [
        'admin_email' => env('FRAUD_ADMIN_EMAIL', 'admin@example.com'),
        'slack_webhook' => env('FRAUD_SLACK_WEBHOOK'),
        'telegram_bot_token' => env('FRAUD_TELEGRAM_BOT_TOKEN'),
        'telegram_chat_id' => env('FRAUD_TELEGRAM_CHAT_ID'),
    ],

    'patterns' => [
        'max_referrals_per_week' => 10,
        'max_same_ip_users' => 5,
        'min_deposit_before_withdrawal' => 50,
        'max_withdrawal_ratio' => 3, // 3x o valor depositado
        'suspicious_ledger_credit_threshold' => 1000,
    ]
];

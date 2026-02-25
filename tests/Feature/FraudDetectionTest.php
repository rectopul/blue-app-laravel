<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FraudDetectionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testDetectsWithdrawalWithoutDeposit()
    {
        $user = User::factory()->create();

        // Criar saque sem depósito
        $user->withdrawals()->create([
            'amount' => 100,
            'status' => 'pending'
        ]);

        $service = new FraudDetectionService();
        $analysis = $service->analyzeUser($user);

        $this->assertGreaterThan(50, $analysis['total_risk_score']);
        $this->assertContains(
            'NO_DEPOSIT_WITHDRAWAL',
            array_column($analysis['alerts'], 'type')
        );
    }

    public function testDetectsSuspiciousIPPattern()
    {
        // Criar 6 usuários com mesmo IP
        $users = User::factory()->count(6)->create(['ip' => '192.168.1.1']);

        $service = new FraudDetectionService();
        $analysis = $service->analyzeUser($users->first());

        $this->assertGreaterThan(40, $analysis['total_risk_score']);
    }
}

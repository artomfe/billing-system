<?php

namespace Tests\Unit;

use App\Jobs\ProcessBill;
use App\Models\Billing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ProcessBillJobTest extends TestCase
{
    use RefreshDatabase;

    // Teste de sucesso
    public function test_process_bill_job_creates_payment_slip_and_updates_billing_status()
    {
        $billing = Billing::factory()->create([
            'status' => Billing::STATUS_PENDING,
            'email' => 'teste@example.com',
            'debtAmount' => 100.00,
            'debtDueDate' => now()->addDays(30),
        ]);

        $job = new ProcessBill($billing);
        $job->handle();

        $this->assertDatabaseHas('payment_slips', [
            'billing_id' => $billing->id,
        ]);
        
        Log::shouldReceive('info');

        $billing->refresh();
        $this->assertEquals(Billing::STATUS_EMAIL_SENT, $billing->status);
    }


    // Teste de erro ao passar o billing Id nulo
    public function test_process_bill_job_logs_error_on_exception()
    {
        Log::shouldReceive('error')->once();

        $billing = Billing::factory()->make();

        $billing->id = null;

        $job = new ProcessBill($billing);
        
        $job->handle();
    }
}

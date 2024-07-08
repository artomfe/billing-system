<?php

namespace Tests\Unit;

use App\Jobs\SendMail;
use App\Models\Billing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SendMailJobTest extends TestCase
{
    use RefreshDatabase;

    //Teste de sucesso
    public function test_send_mail_job_sends_email_and_updates_billing_status()
    {
        Log::shouldReceive('debug');

        $billing = Billing::factory()->create([
            'status' => Billing::STATUS_INVOICE_CREATED,
        ]);

        $job = new SendMail($billing->id);
        $job->handle();

        $billing->refresh();
        $this->assertEquals(Billing::STATUS_EMAIL_SENT, $billing->status);
    }

    //Testando com o billing Id nulo
    public function test_send_mail_job_logs_error_when_billing_id_is_null()
    {
        Log::shouldReceive('error')->once()
            ->with('Billing ID is null. Cannot send email.');

        $job = new SendMail(null);
        $job->handle();
    }

    //Testando com o billing Id inválido
    public function test_send_mail_job_logs_error_when_billing_not_found()
    {
        Log::shouldReceive('error')->once()
            ->with('Billing not found with ID: 99999');

        $job = new SendMail(99999);
        $job->handle();
    }
}

<?php

namespace App\Jobs;

use App\Events\ProcessedBill;
use App\Models\Billing;
use App\Models\PaymentSlip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessBill implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $billing;

    public function __construct(Billing $billing)
    {
        $this->billing = $billing;
    }

    public function handle()
    {
        try {
            // Simulação de geração de documento de pagamento (boleto) 
            // Uma simulação simples pois provavelmente seria uma chamada de url externa
            $urlBoleto = 'https://url-boleto-examplo.com.test/boleto/' . $this->billing->id;
            $codigoDeBarras = '12345678901234567890123456789012345678901234';

            PaymentSlip::updateOrCreate(
                ['billing_id' => $this->billing->id],
                [
                    'url' => $urlBoleto,
                    'bar_code' => $codigoDeBarras,
                ]
            );

            $this->billing->update(['status' => Billing::STATUS_INVOICE_CREATED]);

            event(new ProcessedBill($this->billing));

            Log::info('Documento de pagamento gerado para Billing ID: ' . $this->billing->id);
        } catch (\Exception $e) {
            Log::error('Erro ao processar documento para Billing ID: ' . $this->billing->id . "\n" . $e->getMessage());
        }
    }
}

<?php

namespace App\Jobs;

use App\Models\Billing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $billingId;

    public function __construct($billingId)
    {
        $this->onQueue('processing_send_mail');
        $this->billingId = $billingId;
    }

    public function handle()
    {
        if (is_null($this->billingId)) {
            Log::error('Billing ID is null. Cannot send email.');
            return;
        }

        try {
            $boleto = Billing::findOrFail($this->billingId);
        } catch (\Exception $e) {
            Log::error('Billing not found with ID: ' . $this->billingId);
            return;
        }

        $emailEnviado = $this->enviarEmail($boleto->email);

        if ($emailEnviado) {
            $boleto->update(['status' => Billing::STATUS_EMAIL_SENT]);
        }
    }

    protected function enviarEmail($email)
    {
        try {
            // Simular envio de email:
            // Mail::raw('Simulação de e-mail para boleto.', function ($message) use ($email) {
            //     $message->to($email)
            //             ->subject('E-mail de Boleto Gerado')
            //             ->from('seu-email@dominio.com', 'Nome do Remetente');
            // });

            Log::debug('E-mail enviado com sucesso! Billing ID: ' . $this->billingId);

            return true; 
        } catch (\Exception $e) {
            Log::error('Falha ao enviar e-mail, Billing ID: ' . $this->billingId);
            Log::error("Motivo: \n" . $e);
            return false; 
        }
    }
}

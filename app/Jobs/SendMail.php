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

    protected $boletoId;

    public function __construct($boletoId)
    {
        $this->boletoId = $boletoId;
    }

    public function handle()
    {
        // Buscar o boleto pelo ID
        $boleto = Billing::findOrFail($this->boletoId);

        // Simular o envio de e-mail
        $emailEnviado = $this->enviarEmail($boleto->email);

        // Atualizar o status do boleto se o e-mail foi enviado com sucesso
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

            Log::debug('E-mail enviado com sucesso! Billing Id: ' );

            return true; 
        } catch (\Exception $e) {
            Log::error('Falha ao enviar e-mail, Billing Id: ');
            Log::error("Motivo: \n" . $e);
            return false; 
        }
    }
}

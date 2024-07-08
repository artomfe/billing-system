<?php

namespace App\Listeners;

use App\Events\ProcessedBill;
use App\Models\Billing;
use App\Models\PaymentSlip;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendMailAfterProcessBillListener
{
    use InteractsWithQueue;

    public function handle(ProcessedBill $event)
    {
        $billing = $event->billing;

        $paymentSlip = PaymentSlip::where('billing_id', $billing->id)->first();

        if ($paymentSlip) {
            $email = $billing->email;
            $urlBoleto = $paymentSlip->url;

            // Simulação simples de envio de e-mail:
            // Mail::raw('Link para pagamento: ' . $urlBoleto, function ($message) use ($email) {
            //     $message->to($email)->subject('Link para pagamento');
            // });

            $billing->update(['status' => Billing::STATUS_EMAIL_SENT]);

            Log::info('Email enviado com sucesso! Billing Id: ' . $billing->id);
        } else {
            Log::warning('Não foi possível encontrar o PaymentSlip para Billing ID: ' . $billing->id);
        }
    }
}

<?php

namespace App\Imports;

use App\Models\Billing;
use App\Jobs\ProcessBill;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BillingImport implements ToModel, WithChunkReading, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        if ($this->isValidRow($row)) {
            $existingBilling = Billing::where('debtID', $row['debtid'])->first();

            if ($existingBilling) {
                Log::warning('Registro duplicado encontrado para debtID: ' . $row['debtid']);
                return null;
            }

            $billing =  Billing::create([
                'debtID' => $row['debtid'],
                'name' => $row['name'],
                'governmentId' => $row['governmentid'],
                'email' => $row['email'],
                'debtAmount' => $row['debtamount'],
                'debtDueDate' => $row['debtduedate'],
                'status' => Billing::STATUS_PENDING,
            ]);

            // Job para gerar o documento de pagamento
            ProcessBill::dispatch($billing);

            return $billing;

        } else {
            Log::error('Dados inválidos: ' . json_encode($row));
        }

        return null; 
    }

    public function chunkSize(): int
    {
        return 1000; 
    }

    private function isValidRow(array $row): bool
    {
        return !empty($row['name']) && !empty($row['governmentid']) &&
               !empty($row['email']) && !empty($row['debtamount']) &&
               !empty($row['debtduedate']) && !empty($row['debtid']);
    }
}
<?php

namespace App\Services;

use App\Imports\BillingImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class BillingService
{
    public function processFile($file)
    {
        try {
            Excel::import(new BillingImport, $file);
        } catch (\Exception $e) {
            Log::error('Erro ao importar arquivo no serviço: ' . $e->getMessage());
            throw $e; 
        }
    }
}
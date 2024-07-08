<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessFileRequest;
use App\Services\BillingService;
use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    protected $billingService;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    public function processFile(ProcessFileRequest $request)
    {
        $file = $request->file('input_file');

        try {
            $this->billingService->processFile($file);

            return response()->json(['message' => 'Arquivo processado com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao processar o arquivo: ' . $e->getMessage()], 500);
        }
    }

    public function listBillings(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        try {
            $billings = Billing::orderBy('id', 'DESC')->paginate($perPage);
            return response()->json($billings, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao listar cobranças: ' . $e->getMessage()], 500);
        }
    }
}

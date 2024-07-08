<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\ProcessFileRequest;
use App\Services\BillingService;
use App\Models\Billing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
    * @OA\Info(
    *   title="Sistema de Cobranças API",
    *   version="1.0.0",
    *   description="API utilizada para o Sistema de Cobranças.",
    *   @OA\Contact(
    *     email="artomfe@gmail.com",
    *     name="Support Team"
    *   ),
    * )
     * @group Billing
     *
     * APIs para gerenciamento de cobranças.
*/
class BillingController extends Controller
{
    protected $billingService;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    /**
         * @OA\Post(
         *     path="/api/billing/process-file",
         *     tags={"Billing"},
         *     summary="Processa arquivo CSV de cobranças",
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\MediaType(
         *             mediaType="multipart/form-data",
         *             @OA\Schema(
         *                 @OA\Property(
         *                     property="input_file",
         *                     type="string",
         *                     format="binary"
         *                 )
         *             )
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Arquivo processado com sucesso"
         *     ),
         *     @OA\Response(
         *         response=500,
         *         description="Erro ao processar o arquivo"
         *     )
         * )
    */
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

    /**
         * @OA\Get(
         *     path="/api/billings",
         *     tags={"Billing"},
         *     summary="Lista todas as cobranças",
         *     @OA\Parameter(
         *         name="per_page",
         *         in="query",
         *         description="Número de itens por página",
         *         required=false,
         *         @OA\Schema(
         *             type="integer",
         *             default=10
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Listagem de cobranças",
         *         @OA\JsonContent(
         *             type="object",
         *             @OA\Property(property="data", type="object")
         *         )
         *     ),
         *     @OA\Response(
         *         response=500,
         *         description="Erro ao listar cobranças"
         *     )
         * )
    */
    public function listBillings(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        try {
            $billings = Billing::orderBy('id', 'DESC')->paginate($perPage);

            $billings->getCollection()->transform(function ($bill) {
                $bill->status_display = $bill->status_display;
                return $bill;
            });

            return response()->json($billings, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao listar cobranças: ' . $e->getMessage()], 500);
        }
    }
}

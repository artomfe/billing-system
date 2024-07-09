<?php

namespace Tests\Unit\Imports;

use Tests\TestCase;
use App\Models\Billing;
use App\Jobs\ProcessBill;
use App\Imports\BillingImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillingImportTest extends TestCase
{
    use RefreshDatabase;

    // Testando o store do model
    public function test_model_creation()
    {
        Queue::fake();

        $import = new BillingImport();

        $row = [
            'name' => 'John Testador',
            'governmentid' => '123456789',
            'email' => 'johntestador@example.com',
            'debtamount' => '100.00',
            'debtduedate' => '2024-12-31',
            'debtid' => '1abc4-dfhg-231hajs-12831s',
        ];

        $import->model($row);

        $this->assertDatabaseHas('billings', [
            'debtID' => '1abc4-dfhg-231hajs-12831s',
            'name' => 'John Testador',
            'governmentId' => '123456789',
            'email' => 'johntestador@example.com',
            'debtAmount' => '100.00',
            'debtDueDate' => '2024-12-31',
            'status' => Billing::STATUS_PENDING,
        ]);

    }

    // Testando a validação de registro duplicado
    public function test_duplicate_entry()
    {
        Log::shouldReceive('warning')
            ->once()
            ->with('Registro duplicado encontrado para debtID: 1abc4-dfhg-231hajs-12831s');

        Billing::create([
            'debtID' => '1abc4-dfhg-231hajs-12831s',
            'name' => 'John Testador',
            'governmentId' => '987654321',
            'email' => 'johntestador@example.com',
            'debtAmount' => '200.00',
            'debtDueDate' => '2024-12-31',
            'status' => Billing::STATUS_PENDING,
        ]);

        $import = new BillingImport();

        $row = [
            'name' => 'John Testador',
            'governmentid' => '123456789',
            'email' => 'johntestador@example.com',
            'debtamount' => '100.00',
            'debtduedate' => '2024-12-31',
            'debtid' => '1abc4-dfhg-231hajs-12831s',
        ];

        $billing = $import->model($row);

        $this->assertNull($billing);
    }

    // Testando em caso de falta de dados
    public function test_is_valid_row()
    {
        $import = new BillingImport();

        $validRow = [
            'name' => 'John Testador',
            'governmentid' => '123456789',
            'email' => 'johntestador@example.com',
            'debtamount' => '100.00',
            'debtduedate' => '2024-12-31',
            'debtid' => '1abc4-dfhg-231hajs-12831s',
        ];

        $invalidRow = [
            'name' => 'John Testador',
            'governmentid' => '123456789',
            'email' => 'johntestador@example.com',
            // 'debtamount' => '100.00', // Missing field
            'debtduedate' => '2024-12-31',
            'debtid' => '1abc4-dfhg-231hajs-12831s',
        ];

        $this->assertTrue($import->isValidRow($validRow));
        $this->assertFalse($import->isValidRow($invalidRow));
    }
}

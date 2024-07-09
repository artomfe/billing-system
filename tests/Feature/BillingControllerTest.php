<?php

namespace Tests\Feature;

use App\Models\Billing;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillingControllerTest extends TestCase
{
    use RefreshDatabase;

    // Testando o fluxo completo
    public function test_process_file_integration()
    {
        // Criando um arquivo fake 
        $fileContent = <<<CSV
            name,governmentid,email,debtamount,debtduedate,debtid
            Test Name,123456789,testname@example.com,35.00,2024-07-31,3fddffff-9266-49f2-afb2-355b8e388527
            Name Test,987654321,nametest@example.com,189.99,2024-10-31,e14ccf24-6c3a-436c-829c-13e9dcfbf715
        CSV;

        $file = UploadedFile::fake()->createWithContent('billings.csv', $fileContent);

        $response = $this->postJson('/api/billing/process-file', [
            'input_file' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Arquivo processado com sucesso',
            ]);

        $this->assertDatabaseHas('billings', [
            'debtID' => '3fddffff-9266-49f2-afb2-355b8e388527',
            'name' => 'Test Name',
            'governmentId' => '123456789',
            'email' => 'testname@example.com',
            'debtAmount' => '35.00',
            'debtDueDate' => '2024-07-31',
            'status' => Billing::STATUS_EMAIL_SENT, 
        ]);

        $this->assertDatabaseHas('billings', [
            'debtID' => 'e14ccf24-6c3a-436c-829c-13e9dcfbf715',
            'name' => 'Name Test',
            'governmentId' => '987654321',
            'email' => 'nametest@example.com',
            'debtAmount' => '189.99',
            'debtDueDate' => '2024-10-31',
            'status' => Billing::STATUS_EMAIL_SENT
        ]);
    }
}

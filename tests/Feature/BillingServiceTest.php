<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Billing;
use App\Services\BillingService;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillingServiceTest extends TestCase
{
    use RefreshDatabase;

    // Testando o fluxo completo de importação do service
    public function test_process_file_integration()
    {
        // Simulando um arquivo fake
        $fileContent = <<<CSV
            name,governmentid,email,debtamount,debtduedate,debtid
            Test Name,123456789,testname@example.com,10290.77,2024-07-31,3fddffff-9266-49f2-afb2-355b8e388527
            Name Test,987654321,nametest@example.com,189.12,2024-10-31,e14ccf24-6c3a-436c-829c-13e9dcfbf715
        CSV;
        $file = UploadedFile::fake()->createWithContent('billings.csv',$fileContent);

        $service = new BillingService();

        $service->processFile($file);

        $this->assertDatabaseHas('billings', [
            'debtID' => '3fddffff-9266-49f2-afb2-355b8e388527',
            'governmentId' => '123456789',
            'email' => 'testname@example.com',
            'debtAmount' => '10290.77',
            'debtDueDate' => '2024-07-31',
            'status' => Billing::STATUS_EMAIL_SENT,
        ]);

        $this->assertDatabaseHas('billings', [
            'debtID' => 'e14ccf24-6c3a-436c-829c-13e9dcfbf715',
            'name' => 'Name Test',
            'governmentId' => '987654321',
            'email' => 'nametest@example.com',
            'debtAmount' => '189.12',
            'debtDueDate' => '2024-10-31',
            'status' => Billing::STATUS_EMAIL_SENT,
        ]);
    }
}
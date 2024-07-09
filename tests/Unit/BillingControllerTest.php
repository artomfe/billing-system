<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Billing;
use App\Http\Controllers\API\BillingController;
use App\Services\BillingService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Mockery;

class BillingControllerTest extends TestCase
{
    use RefreshDatabase;

    // Testando retorno HTTP 200 para listagem de billings
    public function test_list_billings_success()
    {
        Billing::factory()->count(5)->create();

        $request = Request::create('/api/billings', 'GET');
        $controller = new BillingController(new BillingService());

        $response = $controller->listBillings($request);

        $this->assertEquals(200, $response->getStatusCode());
    }


    // Testando a paginação da listagem de billings
    public function test_list_billings_pagination()
    {
        Billing::factory()->count(15)->create();

        $response = $this->getJson('/api/billings?per_page=5');

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data');
    }
}

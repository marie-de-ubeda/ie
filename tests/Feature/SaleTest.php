<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SaleTest extends TestCase
{
    use RefreshDatabase;
	
    /**
     * Fetch Stack Trace in console log
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }
    
    /**
     * @return void
     */
    public function test_a_sale_can_be_created()
    {
        $response = $this->post(
            '/v1/categories',
            [
                'name'=>"BROCANTE - VENTE UNIQUEMENT EN LIVE",
            ]
        );
        $this->assertCount(1, Sale::all());
        $response->assertStatus(201);
    }
}

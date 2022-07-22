<?php

namespace Tests\Feature;

use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
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
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_category_can_be_created()
    {
        $response = $this->post(
            '/v1/categories',
            [
                'name'=>"Vente de mobilier courant",
                'summary'=>"Chineur invétéré ou novice, les ventes courantes vous offrent une variété de lots à des prix accessibles."
            ]
        );
        $this->assertCount(1, Category::all());
        $response->assertStatus(201);
    }
    
    public function test_name_and_summary_cannot_be_null()
    {
        $response = $this->post(
            '/v1/categories',
            [
                'name'=>"",
                'summary'=>""
            ]
        );
        $response->assertSessionHasErrors(['name','summary']);
    }
}

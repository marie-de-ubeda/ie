<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_an_item_can_be_created()
    {
        $response = $this->post(
            '/v1/items',
            [
                'category_id'=>1,
                'sale_id'=>1,
                'description'=>"Affiche \"Automobilia\" le nouveau record du monde de l'heure , \r\nCastrol sur voiture Bugatti",
                'auction_type'=>"live",
                'pricing' =>
                    [
                        'estimates'=>
                            [
                                'max'=>500,
                                'min'=>300,
                                'currency'=>'euro'
                            ]
                    ]
            ]
        );
        $this->assertCount(1, Item::all());
        $response->assertStatus(201);
    }
}

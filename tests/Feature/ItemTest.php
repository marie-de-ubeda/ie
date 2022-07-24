<?php

namespace Tests\Feature;

use App\Item;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTest extends TestCase
{
//    use RefreshDatabase;
    
//    /**
//     * Fetch Stack Trace in console log
//     * @return void
//     */
    protected function setUp(): void
    {
        parent::setUp();
//        $this->withoutExceptionHandling();
    }
    
    /**
     * Success Test An Item Can Be Created and Json Structure Check
     * @return void
     */
    public function test_an_item_can_be_created()
    {
        $data = [
            'id'=>1,
            'category_id'=>2,
            'sale_id'=>2,
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
        ];
        $response = $this->post('/v1/items', $data);
//        $this->assertCount(1, Item::all());
        $structure =[
            "id",
            "category"=>[
                    'id',
                    'name',
                    'summary'
            ],
            "sale" => [
                    'id',
                    'name',
            ],
            "description",
            "auction_type",
            "pricing"=>[
                'estimates'=>[
                    'max',
                    'min',
                    'currency'
                ]
            ],
            "last_updated"];

        $response->assertJsonStructure($structure);
        $response->assertStatus(201);
    }
    
    /**
     * Test category_id_foreign_key_constraint
     * @return void
     */
    public function test_item_category_id_foreign_key_constraint()
    {
        $response = $this->json(
            'post',
            '/v1/items',
            [
                'id'=>1,
                'category_id'=>17,
                'sale_id'=>2,
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
        $response->assertJsonValidationErrors(['category_id']);
    }
    
    /**
     * Test sale_id_foreign_key_constraint
     * @return void
     */
    public function test_item_sale_id_foreign_key_constraint()
    {
        $response = $this->json(
            'post',
            '/v1/items',
            [
                'id'=>1,
                'category_id'=>2,
                'sale_id'=>45,
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
        $response->assertJsonValidationErrors(['sale_id']);
    }
    
    
    public function test_description_required()
    {
        $response = $this->json(
            'POST',
            '/v1/items',
            [
                'id'=>1,
                'category_id'=>2,
                'sale_id'=>2,
                'description'=>"",
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
        $response->assertJsonValidationErrors(['description']);
        $this->assertCount(0, Item::where('description', '=', null)->get());
    }
    
    public function test_auction_type_required()
    {
        $response = $this->json(
            'POST',
            '/v1/items',
            [
                'id'=>1,
                'category_id'=>2,
                'sale_id'=>2,
                'description'=>"Affiche \"Automobilia\" le nouveau record du monde de l'heure , \r\nCastrol sur voiture Bugatti",
                'auction_type'=>"",
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
        $response->assertJsonValidationErrors(['auction_type']);
        $this->assertCount(0, Item::where('auction_type', '=', null)->get());
    }
    
    
    public function test_auction_type_string()
    {
        $response = $this->json(
            'POST',
            '/v1/items',
            [
                'id'=>1,
                'category_id'=>2,
                'sale_id'=>2,
                'description'=>"Affiche \"Automobilia\" le nouveau record du monde de l'heure , \r\nCastrol sur voiture Bugatti",
                'auction_type'=>5,
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
        $response->assertJsonValidationErrors(['auction_type']);
    }
    
    public function test_auction_type_max()
    {
        $response = $this->json(
            'POST',
            '/v1/items',
            [
                'id'=>1,
                'category_id'=>2,
                'sale_id'=>2,
                'description'=>"Affiche \"Automobilia\" le nouveau record du monde de l'heure , \r\nCastrol sur voiture Bugatti",
                'auction_type'=>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer iaculis purus ac nunc aliquam commodo. Proin auctor eros at metus pulvinar condimentum. Donec vel pulvinar purus, sit amet sodales tellus. Etiam erat ante, egestas et auctor et, placerat at tellus. Fusce placerat iaculis dolor quis id.",
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
        $response->assertJsonValidationErrors(['auction_type']);
    }
    
    public function test_pricing_required()
    {
        $response = $this->json(
            'POST',
            '/v1/items',
            [
                'id'=>1,
                'category_id'=>2,
                'sale_id'=>2,
                'description'=>"Affiche \"Automobilia\" le nouveau record du monde de l'heure , \r\nCastrol sur voiture Bugatti",
                'auction_type'=>"live",
                
            ]
        );
        $response->assertJsonValidationErrors(['pricing']);
    }
    
    public function test_pricing_array_size_equals_1()
    {
        $datas =
        [
            'id'=>1,
            'category_id'=>2,
            'sale_id'=>2,
            'description'=>"Affiche \"Automobilia\" le nouveau record du monde de l'heure , \r\nCastrol sur voiture Bugatti",
            'auction_type'=>"Lorem ipsum dolor sit amet, consectetur .",
            'pricing' =>
                [
                    'estimates'=>
                        [
                            'max'=>500,
                            'min'=>300,
                            'currency'=>'euro'
                        ],
                    'fake_key'=>'fake value'
                ]
        ];
        $response = $this->json(
            'POST',
            '/v1/items',
            $datas
        );
      
        $response->assertJsonValidationErrors(['pricing']);
    }
    
    public function test_pricing_estimates_key_exist()
    {
        $datas =
            [
                'id'=>1,
                'category_id'=>2,
                'sale_id'=>2,
                'description'=>"Affiche \"Automobilia\" le nouveau record du monde de l'heure , \r\nCastrol sur voiture Bugatti",
                'auction_type'=>"Lorem ipsum dolor sit amet, consectetur .",
                'pricing' =>
                    [
                        'estimatsses'=>
                            [
                                'max'=>500,
                                'min'=>300,
                                'currency'=>'euro'
                            ],
                    ]
            ];
        $response = $this->json(
            'POST',
            '/v1/items',
            $datas
        );
        
        $response->assertJsonValidationErrors(['pricing.estimates']);
    }
    
    public function test_pricing_estimates_size_equals_3()
    {
        $datas =
            [
                'id'=>1,
                'category_id'=>2,
                'sale_id'=>2,
                'description'=>"Affiche \"Automobilia\" le nouveau record du monde de l'heure , \r\nCastrol sur voiture Bugatti",
                'auction_type'=>"Lorem ipsum dolor sit amet, consectetur .",
                'pricing' =>
                    [
                        'estimates'=>
                            [
                                'max'=>500,
                                'min'=>300,
                                //'currency'=>'euro'
                            ],
                    ]
            ];
        $response = $this->json(
            'POST',
            '/v1/items',
            $datas
        );
        
        $response->assertJsonValidationErrors(['pricing.estimates']);
    }
    
    public function test_pricing_estimates_min_must_be_numeric()
    {
        $datas =
            [
                'id'=>1,
                'category_id'=>2,
                'sale_id'=>2,
                'description'=>"Affiche \"Automobilia\" le nouveau record du monde de l'heure , \r\nCastrol sur voiture Bugatti",
                'auction_type'=>"Lorem ipsum dolor sit amet, consectetur .",
                'pricing' =>
                    [
                        'estimates'=>
                            [
                                'max'=>500,
                                'min'=>"non numeric value",
                                'currency'=>'euro'
                            ],
                    ]
            ];
        $response = $this->json(
            'POST',
            '/v1/items',
            $datas
        );
        
        $response->assertJsonValidationErrors(['pricing.estimates.min']);
    }
    
    public function test_pricing_estimates_min_must_gt_0()
    {
        $datas =
            [
                'id'=>1,
                'category_id'=>2,
                'sale_id'=>2,
                'description'=>"Affiche \"Automobilia\" le nouveau record du monde de l'heure , \r\nCastrol sur voiture Bugatti",
                'auction_type'=>"Lorem ipsum dolor sit amet, consectetur .",
                'pricing' =>
                    [
                        'estimates'=>
                            [
                                'max'=>500,
                                'min'=>0,
                                'currency'=>'euro'
                            ],
                    ]
            ];
        $response = $this->json(
            'POST',
            '/v1/items',
            $datas
        );
        
        $response->assertJsonValidationErrors(['pricing.estimates.min']);
    }
    
    public function test_pricing_estimates_max_must_be_numeric()
    {
        $datas =
            [
                'id'=>1,
                'category_id'=>2,
                'sale_id'=>2,
                'description'=>"Affiche \"Automobilia\" le nouveau record du monde de l'heure , \r\nCastrol sur voiture Bugatti",
                'auction_type'=>"Lorem ipsum dolor sit amet, consectetur .",
                'pricing' =>
                    [
                        'estimates'=>
                            [
                                'max'=>"non numeric value",
                                'min'=>10,
                                'currency'=>'euro'
                            ],
                    ]
            ];
        $response = $this->json(
            'POST',
            '/v1/items',
            $datas
        );
        
        $response->assertJsonValidationErrors(['pricing.estimates.max']);
    }
    public function test_pricing_estimates_max_must_be_gt_min()
    {
        $datas =
            [
                'id'=>1,
                'category_id'=>2,
                'sale_id'=>2,
                'description'=>"Affiche \"Automobilia\" le nouveau record du monde de l'heure , \r\nCastrol sur voiture Bugatti",
                'auction_type'=>"Lorem ipsum dolor sit amet, consectetur .",
                'pricing' =>
                    [
                        'estimates'=>
                            [
                                'max'=>100,
                                'min'=>300,
                                'currency'=>'euro'
                            ],
                    ]
            ];
        $response = $this->json(
            'POST',
            '/v1/items',
            $datas
        );
        
        $response->assertJsonValidationErrors(['pricing.estimates.max']);
    }
    
    public function test_pricing_estimates_currency_must_be_string()
    {
        $datas =
            [
                'id'=>1,
                'category_id'=>2,
                'sale_id'=>2,
                'description'=>"Affiche \"Automobilia\" le nouveau record du monde de l'heure , \r\nCastrol sur voiture Bugatti",
                'auction_type'=>"Lorem ipsum dolor sit amet, consectetur .",
                'pricing' =>
                    [
                        'estimates'=>
                            [
                                'max'=>500,
                                'min'=>100,
                                'currency'=>123
                            ],
                    ]
            ];
        $response = $this->json(
            'POST',
            '/v1/items',
            $datas
        );
        
        $response->assertJsonValidationErrors(['pricing.estimates.currency']);
    }
}

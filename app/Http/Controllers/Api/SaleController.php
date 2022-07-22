<?php

namespace App\Http\Controllers\Api;

use App\Sale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SaleController extends Controller
{
    /**
     * Sale Store
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $category = Sale::create([
            "name" => $request->name,
        ]);
        
        return response()->json(
            [
                "id" => $category->id,
                "name" => $category->name,
            ],
            Response::HTTP_CREATED
        );
    }
}

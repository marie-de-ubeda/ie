<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SaleStoreRequest;
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
    public function store(SaleStoreRequest $request)
    {
        $sale = Sale::create([
            "name" => $request->name,
        ]);
        
        return response()->json(
            [
                "id" => $sale->id,
                "name" => $sale->name,
            ],
            Response::HTTP_CREATED
        );
    }
}

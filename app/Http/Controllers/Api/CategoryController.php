<?php

namespace App\Http\Controllers\Api;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Category Store
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $category = Category::create([
            "name" => $request->name,
            "summary" => $request->summary,
        ]);

        return response()->json(
            [
                "id" => $category->id,
                "name" => $category->name,
                "summary" => $category->summary,
            ],
            Response::HTTP_CREATED
        );
    }
}

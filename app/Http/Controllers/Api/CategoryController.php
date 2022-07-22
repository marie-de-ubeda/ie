<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Category Store
     * @param CategoryStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryStoreRequest $request)
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

<?php

namespace App\Http\Controllers\Api;

use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ItemController extends Controller
{
    public function store(Request $request)
    {
    
        $item = new Item();
        $datas = $request->all();
        $item->fill($datas)->save();
        
//        return response()->json(
//            [
//                "id" => $item->id,
//                "description" => $item->description,
//                "pricing"=>$item->pricing
//            ],
//            Response::HTTP_CREATED
//        );
    }
}

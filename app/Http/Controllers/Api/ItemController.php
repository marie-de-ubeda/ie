<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ItemStoreRequest;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ItemController extends Controller
{
    public function store(ItemStoreRequest $request)
    {
    
        $item = new Item();
        $datas = $request->all();
        $item->fill($datas)->save();
//		dd($item->id);
        
        return response()->json(
            [
                "id" => $item->id,
                "category" => $item->category,
                "sale" => $item->sale,
                "description" => $item->description,
                "auction_type" => $item->auction_type,
                "pricing" => $item->pricing,
                'last_updated'=>$item->last_updated
            //                "pricing"=>$item->pricing
            ],
            Response::HTTP_CREATED
        );
    }
}

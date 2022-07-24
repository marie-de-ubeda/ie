<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ItemStoreRequest;
use App\Http\Resources\ItemCollection;
use App\Http\Resources\ItemResource;
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
		
        return new ItemResource($item);
    }
    
    public function index(Request $request)
    {
        if ($request->auction_type =="live") {
            $items = Item::where('auction_type', '=', 'live')->get();
        } else {
            $items = Item::all();
        }
		
        return new ItemCollection($items);
    }
    
    public function show(Item $item)
    {
        return new ItemResource($item);
    }
}

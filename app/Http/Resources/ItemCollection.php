<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemCollection extends ResourceCollection
{
	
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		return
			
			$this->collection->transform(function ($item) {
				return
					[
						"id" => $item->id,
						"category" => $item->category,
						"sale" => $item->sale,
						"description" => $item->description,
						"auction_type" => $item->auction_type,
						"pricing" => $item->pricing,
						'last_updated'=>$item->last_updated
					];
			}
			)
		
		;
    
    }
}

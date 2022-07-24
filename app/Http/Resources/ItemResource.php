<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
	public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "category" => $this->category,
            "sale" => $this->sale,
            "description" => $this->description,
            "auction_type" => $this->auction_type,
            "pricing" => $this->pricing,
            'last_updated'=>$this->last_updated
        ];
    }
}

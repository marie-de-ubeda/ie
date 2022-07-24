<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	
    protected $fillable =['category_id','sale_id','description','auction_type','pricing'];
    protected $casts = [
        'pricing' => 'array',
        'pricing.estimates'=>'array'
    ];
    
    const UPDATED_AT = "last_updated";
    
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function sale(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}

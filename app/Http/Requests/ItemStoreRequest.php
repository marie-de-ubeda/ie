<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class ItemStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'=>'required|unique:items|numeric',
            'category_id'=>'required|numeric|exists:categories,id',
            'sale_id'=>'required|numeric|exists:sales,id',
            'description'=>'required|string',
            'auction_type'=>'required|string|max:255',
            'pricing'=> 'required|array|size:1',
            'pricing.estimates'=>'required|size:3',
            'pricing.estimates.max'=>'required|numeric|gt:pricing.estimates.min',
            'pricing.estimates.min'=>'required|numeric|gt:0',
            'pricing.estimates.currency'=>'required|string'
        ];
    }
    
//  public function attributes()
//  {
//      return ['description'];
//  }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.required' => "ValidationError: id is required",
            'id.numeric' => "ValidationError: id should be numerical, got 'badly-formatted-id' instead",
            'category_id.required' => 'ValidationError: property category_id is required',
            'category_id.numeric' => 'ValidationError: property category_id must be numeric',
            'sale_id.required' => 'ValidationError: property sale_id is required',
            'sale_id.numeric' => 'ValidationError: property sale_id is must be numeric',
            'description.required' => 'ValidationError: property description is required',
            'description.string' => 'ValidationError: property description is required',
            'auction_type.required' => 'ValidationError: missing property auction_type',
            'auction_type.string' => 'ValidationError: property auction_type must be a string',
            'auction_type.max' => 'ValidationError: property auction_type max length is 255',
            'pricing.required' => 'ValidationError: property pricing is required',
            'pricing.array' => 'ValidationError: property pricing must be array',
            'pricing.size' => 'ValidationError: property pricing must be array with 1 key > estimates',
            'pricing.estimates.required' => 'ValidationError: property pricing.estimates is required',
            'pricing.estimates.size' => 'ValidationError: property pricing.estimates must be array with 3 keys > max,min,currency',
            'pricing.estimates.min' => 'ValidationError: property pricing.estimates.min must numeric and greater than 0',
            'pricing.estimates.max' => 'ValidationError: property pricing.estimates.max must numeric',
            'pricing.estimates.currency' => 'ValidationError: property pricing.estimates.currency must be a string',
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
       
        $id_type_error = $validator->errors()->get("id");
        $auction_type_error = $validator->errors()->get("auction_type");
//      dd($auction_type_error);
        
        if ($id_type_error ==="The id has already been taken.") {
            throw new HttpResponseException(response()->noContent(409));
        } elseif ($id_type_error ==="ValidationError: id should be numerical, got 'badly-formatted-id' instead" ||
            $auction_type_error ==="ValidationError: missing property auction_type") {
            $response = [
            'status' => true,
            'error' => $validator->errors()->first(),
            ];
            throw new HttpResponseException(response()->json($response, 400));
        } elseif ($this->wantsJson()) {
            $response = response()->json([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ]);
//          dd($response);
            throw new HttpResponseException($response, 422);
        }
    }
}

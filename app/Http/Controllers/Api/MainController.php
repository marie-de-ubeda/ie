<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    /**
     * Api Check Status.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkApiStatus()
    {
        return response()->json(['status' => 'OK']);
    }
}

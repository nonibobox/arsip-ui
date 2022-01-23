<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ArsipController extends Controller
{
    public function index()
    {
        $page_title = 'Manajemen Arsip';
        $page_description = 'Manajemen arsip';

        try{
            $response = Http::withToken(session('access_token'))->acceptJson()
                ->get(config('services.api_gateway.endpoint').'/arsip-tahunan/2022');
            $models = ($response->successful()) ? $response->json()["data"]["data"]: [];
        }catch(\Exception $e){
            return ['message' => 'error ' . $e->getMessage(), 'status' => 500];
        }

        return view('arsip', compact('models', 'page_title', 'page_description'));
    }
}

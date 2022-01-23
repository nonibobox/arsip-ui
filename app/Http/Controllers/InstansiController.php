<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InstansiController extends Controller
{
    public function index()
    {
        $page_title = 'Manajemen Instansi';
        $page_description = 'Manajemen instansi';
        try{
            $response = Http::withToken(session('access_token'))
                ->acceptJson()->get(config('services.api_gateway.endpoint').'/instansi');
            $models = ($response->successful()) ? $response->json()["data"]: [];
        }catch(\Exception $e){
            return ['message' => 'error ' . $e->getMessage(), 'status' => 500];
        }
        
        return view('instansi', compact('models', 'page_title', 'page_description'));
    }
}

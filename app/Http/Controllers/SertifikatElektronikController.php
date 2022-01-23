<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SertifikatElektronikController extends Controller
{
    public function index()
    {
        $page_title = 'Manajemen Sertifikat Elektronik';
        $page_description = 'Manajemen sertifikat elektronik';

        try{
            $response = Http::withToken(session('access_token'))
                ->acceptJson()->get(config('services.api_gateway.endpoint').'/sertifikat-elektronik');
            $models = ($response->successful()) ? $response->json()["data"]: [];
        }catch(\Exception $e){
            return ['message' => 'error ' . $e->getMessage(), 'status' => 500];
        }

        return view('sertifikat_elektronik', compact('models', 'page_title', 'page_description'));
    }
}

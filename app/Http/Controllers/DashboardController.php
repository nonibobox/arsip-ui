<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $page_title = 'Manajemen Arsip';
        $page_description = 'Manajemen arsip';
        $models = [];

        try {

            for ($tahun = 2014; $tahun <= 2022; $tahun++) {
                if (!Cache::has("jumlah_arsip_$tahun")) {
                    $response = Http::withToken(session('access_token'))
                        ->acceptJson()->get(config('services.api_gateway.endpoint') . "/count-arsip-tahunan/$tahun");
                    Cache::put("jumlah_arsip_$tahun", $response->json()["data"]["data"], now()->addMinutes(10));
                }

                $models = Arr::add($models, "$tahun", Cache::get("jumlah_arsip_$tahun"));
            }
            //$models = ($response->successful()) ? $response->json()["data"] : [];
        } catch (\Exception $e) {
            return ['message' => 'error ' . $e->getMessage(), 'status' => 500];
        }

        return view('dashboard', compact('models', 'page_title', 'page_description'));
    }
}

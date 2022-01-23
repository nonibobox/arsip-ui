<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;

class AuthenticatePassport
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('Authorization');

        if (!empty($token)) {
            
            $result = $this->get_profile_sso($request);
            
            if ($result['status'] != 200) {
                return response()->json(['message' => 'Unauthorized', 'success' => false, 'code' => 401], 401);
            } else {
                // $user = User::firstOrCreate(
                //     ['email'=>$result['data']['email']],
                //     [
                //         'name'=>$result['data']['name'],
                //         'password'=>Hash::make($result['data']['name'])
                //     ]
                // );
                $user = User::firstOrCreate(
                    ['email'=>"ninon.nurulfaiza@gmail.com"],
                    [
                        'name'=>"ninon.nurulfaiza@gmail.com",
                        'password'=>Hash::make("ninon.nurulfaiza@gmail.com")
                    ]
                );
                return $next($request);
            }
        } else {
            return response()->json(['message' => 'Missing Authentication Token'], 403);
        }
    }

    public function get_profile_sso(Request $request)
    {
        try {
            $token = $request->header('Authorization');

            // if (empty($token)) return response()->json(['message' => 'Missing Authentication Token'], 403);

            // $http = new Client;
            // $response = $http->post('http://127.0.0.1:8001/oauth/token', [
            //     'form_params' => [
            //         'grant_type' => 'client_credentials',
            //         'client_id' => '4',
            //         'client_secret' => 'mF3QwOJ8pptM9YcRrv7pYQkiGKvFeSuSlZf0TXCw',
            //         'scope' => '',
            //     ],
            // ]);
            // $response = $http->post('http://127.0.0.1:8001/oauth/token', [
            //     'form_params' => [
            //         'grant_type' => 'password',
            //         'client_id' => '7',
            //         'client_secret' => 'QizfCfU4HN5n48hShjG61HJweygxKganGGhHNsK4',
            //         'scope' => '',
            //         'username' => 'ninon@gmail.com',
            //         'password' => 'rahasia123',
            //     ],
            // ]);
            // $response = $http->post('http://localhost/api-gateway/public/oauth/token', [
            //     'form_params' => [
            //         'grant_type' => 'password',
            //         'client_id' => '8',
            //         'client_secret' => 'mJMad9vztoiT6aij1rh7wyXJXi0HdnAwY9XFddzr',
            //         'scope' => '',
            //         'username' => 'ninon.nurul@gmail.com',
            //         'password' => 'ninon123',
            //     ],
            // ]);

            //$token = json_decode($response->getBody()->getContents())->access_token;
            $response = Http::withToken($token,'')->acceptJson()->get("http://127.0.0.1:8001/user");
//dd($response);
            $responseCode = array("200", "201");
            if (in_array($response->status(), $responseCode)) {
                $user = $response->json();
                $message = 'success';
                //$user = json_decode($response->getBody()->getContents());

                $status = 200;
            } elseif ($response->status() == 401) {
                // dd($response);
                $status = 401;
                $message = json_decode($response->getBody()->getContents())->message;
                $user = [];
            } else {
                $user = [];
                $status = 500;
                $message = json_decode($response->getBody()->getContents())->message;
            }

            return ['status' => $status, 'message' => $message, 'token' => $token, 'data' => $user];
        } catch (\Exception $e) {
            return ['message' => 'error ' . $e->getMessage(), 'status' => 500];
        }
    }
}

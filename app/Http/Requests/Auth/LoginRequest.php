<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function loginPassport(){
        return Http::withBasicAuth(env('OAUTH2_CLIENT_ID'), env('OAUTH2_CLIENT_SECRET'))
                ->asForm()->post(config('services.api_gateway.login_endpoint'), [
                    'grant_type' => 'password',
                    'username' => $this->email,
                    'password' => $this->password
        ])->json();
    }

    public function getUserPassport($access_token){
        return Http::withToken($access_token)
                ->get(config('services.api_gateway.user_endpoint'))->json();
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        $response = $this->loginPassport();

        if(empty($response['access_token'])){
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }else{
            $this->session()->put('access_token', $response['access_token']);
            $user = $this->getUserPassport($response['access_token']);
            User::firstOrCreate(
                ['email'=>$user['email']],
                [
                    'name'=>$user['name'],
                    'password'=>Hash::make($this->password)
                ]
            );
            Auth::attempt($this->only('email', 'password'), $this->boolean('remember'));
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}

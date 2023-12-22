<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Client;
use Illuminate\Foundation\Validation\ValidatesRequests;


class LoginController
{
    use IssueTokenTrait;
    use ValidatesRequests;
    private $client;

    public function __construct()
    {
        $this->client = Client::find(2);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        return $this->issueToken($request,'password');
    }
    
    public function refresh(Request $request)
    {
        $this->validate($request, [
            'refresh_token' => 'required'
        ]);

        return $this->issueToken($request, 'password');
    }

    public function logout(Request $request) 
    {
        $accessToken = Auth::user()->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken)
            ->update(['revoked' => true]);

        
        return response()->json(204);
    }

}

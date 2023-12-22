<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Laravel\Passport\Client;
use App\Models\User;


class RegisterController extends Controller
{
    use IssueTokenTrait;
    private $client;

    public function __construct() 
    {
        $this->client = Client::find(2);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::min(6)]
        ]);

        User::create([
            'name' => $request['name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => $request['password']

        ]);

        return $this->issueToken($request,'password');
    }
}

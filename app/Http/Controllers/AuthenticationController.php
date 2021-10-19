<?php

namespace App\Http\Controllers;
use App\Transformers\AuthenticationTransformer;

use App\Http\Requests\AuthenticationRequest as Request;
use App\Models\User;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $user = User::find($request->get('customer_xid'));
        $token = auth()->login($user);

        return fractal([
            'auth' => ['token' => $token]
        ], new AuthenticationTransformer())->respond();
    }
}

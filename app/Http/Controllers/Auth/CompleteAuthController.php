<?php

namespace App\Http\Controllers;

use App\Http\Middleware\PrepareValidateData;
use App\Models\AuthCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompleteAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(PrepareValidateData::class)->only('signUp');
    }

    public function checkAuthCode(Request $request) 
    {
        $authResult = AuthCode::join('users', 'auth_codes.user_id', '=', 'users.id')
                            ->where('users.email', $request->email)
                            ->where('auth_codes.code', $request->code)
                            ->where('auth_codes.expiry', '>', Carbon::now())
                            ->first();
    }
}

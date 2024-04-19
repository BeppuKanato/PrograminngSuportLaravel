<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\PrepareValidateData;
use Illuminate\Http\Request;
use App\Http\Requests\SignUpRequest;

class SignUpController extends Controller
{
    public function __construct()
    {
        $this->middleware(PrepareValidateData::class)->only('signUp');
    }
    public function signUp(SignUpRequest $request)
    {
        return response()->json([
            'id' => $request->id,
            'mail' => $request->mail,
        ]);
    }
}


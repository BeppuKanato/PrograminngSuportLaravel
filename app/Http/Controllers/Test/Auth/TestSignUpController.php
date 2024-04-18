<?php

namespace App\Http\Controllers\Test\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\PrepareValidateData;
use Illuminate\Http\Request;
use App\Http\Requests\SignUpRequest;

class TestSignUpController extends Controller
{
    public function __construct()
    {
        $this->middleware(PrepareValidateData::class)->only('signUp');
    }
    public function signUp(SignUpRequest $request)
    {
        var_dump('正しい値です');
    }
}


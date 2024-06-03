<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Middleware\PrepareValidateData;
use App\Http\Requests\Auth\SignInRequest;
use App\Models\User;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SignInController extends Controller
{
    function __construct() 
    {
        $this->middleware(PrepareValidateData::class)->only('signIn');
    }

    public function signIn(SignInRequest $request)
    {
        $authResult = $this->checkUser($request->email, $request->password);
        if ($authResult == null) 
        {
            var_dump('サインインに失敗しました');
            die;
        }

        return response()->json($authResult);
    }
    //ユーザ認証を行う
    function checkUser(string $email, string $password) 
    {
        $result = null;

        $checkUserResult = User::where('email', $email)
                    ->whereNotNull('email_verified_at')
                    ->first();

        if ($checkUserResult != null) 
        {
            $checkPassword = $this->checkPassword($password, $checkUserResult->password);
            //パスワード認証を通った場合
            if ($checkPassword) 
            {
                $checkUserResult->remember_token = $this->createRememberToken();
                $checkUserResult->save();
                $result = [
                    'id' => $checkUserResult->id,
                    'email' => $checkUserResult->email,
                    'name' => $checkUserResult->name,
                    'remember_token' => $checkUserResult->remember_token
                ];
            }
        }

        return $result;
    }
    //パスワードの照合を行う
    function checkPassword(string $inputPassword, string $dbPassword) 
    {
        $result = false;
        if (Hash::check($inputPassword, $dbPassword)) 
        {
            $result = true;
        }

        return $result;
    }
    //アクセストークンを作成する
    function createRememberToken() 
    {
        return Str::random(60);
    }
}

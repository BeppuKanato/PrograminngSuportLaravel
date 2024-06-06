<?php
namespace App\Http\Controllers\Auth;

use App\Http\Middleware\PrepareValidateData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\AuthRememberTokenRequest;
use App\Models\User;

class AuthRememberTokenController extends Controller
{
    function __construct() 
    {
        $this->middleware(PrepareValidateData::class)->only('authRememberToken');
    }

    public function authRememberToken(AuthRememberTokenRequest $request) 
    {
        $resultCheckRememberToken = $this->checkCorrectRememberToken($request->id, $request->rememberToken);

        if ($resultCheckRememberToken) 
        {
            $message = [
                'message' => 'ユーザーの認証に成功しました',
            ];
            return response()->json($message);
        }
        else
        {
            $message = [
                'message' => 'ユーザーの認証に失敗しました',
            ];
            return response()->json($message, 422);
        }
    }

    function checkCorrectRememberToken(int $id, string $rememberToken) 
    {
        $result = false;
        $checkRememberTokenResult = User::where('id', $id)
                                    ->where('remember_token', $rememberToken)
                                    ->first();

        if ($checkRememberTokenResult != null) 
        {
            $result = true;
        }

        return $result;
    }
}

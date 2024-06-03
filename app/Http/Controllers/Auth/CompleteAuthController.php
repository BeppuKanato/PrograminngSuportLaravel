<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\CompleteAuthRequest;
use App\Http\Middleware\PrepareValidateData;
use App\Models\AuthCode;
use App\Models\User;
use Carbon\Carbon;

class CompleteAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(PrepareValidateData::class)->only('checkAuthCode');
    }

    public function checkAuthCode(CompleteAuthRequest $request) 
    {
        $result = $this->getAuthResult($request->id, $request->code);

        if ($result == null) 
        {
            var_dump('認証に失敗しました');
            die;
        }

        $this->upDateEmailVerifiedAt($result->id);
        $this->deleteAuthCode($result->email);
    }
    //認証可能なユーザを取得
    function getAuthResult(int $id, string $code) 
    {
        $result = AuthCode::join('users', 'auth_codes.user_id', '=', 'users.id')
                            ->where('users.id', $id)
                            ->where('auth_codes.code', $code)
                            ->where('auth_codes.expiry', '>', Carbon::now())
                            ->select('users.id', 'users.email')
                            ->first();
        
        var_dump('認証可能ユーザを確認しました');
        return $result;
    }
    //ユーザのemail-verifield-atを更新
    function upDateEmailVerifiedAt(int $id) 
    {
        User::where('id', $id)
            ->update(['email_verified_at' => Carbon::now()]);

        var_dump('varidiedAtを更新しました');
    }
    //認証したメールアドレスまたは有効期限切れの認証コードを物理削除
    function deleteAuthCode(string $email) 
    {
        AuthCode::join('users', 'auth_codes.user_id', '=', 'users.id')
                ->where('users.email', $email)
                ->orWhere('expiry', '<', Carbon::now())
                ->delete();

        var_dump('認証コードを削除しました');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\PrepareValidateData;
use App\Http\Requests\Auth\SignOutRequest;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignOutController extends Controller
{
    function __construct()
    {
        $this->middleware(PrepareValidateData::class)->only('signOut');
    }

    public function signOut(SignOutRequest $request) 
    {
        $passAndAccessToken = $this->getPassAndAccessToken($request->id);

        if ($passAndAccessToken == null) 
        {
            var_dump('idが正しくありません');
            die;
        }

        $password = $passAndAccessToken->password;
        $accessToken = $passAndAccessToken->token;

        $passwordCheckResult = $this->checkPassword($request->password, $password);
        $accessTokenCheckResult = $this->checkAccessToken($request->accessToken, $accessToken);

        if (!$passwordCheckResult || !$accessTokenCheckResult) 
        {
            var_dump('パスワードが正しくないかエラーが発生しています');
            die;
        }

        $this->deleteAccessToken($request->id);
    }

    //パスワード、アクセストークンを取得
    function getPassAndAccessToken(int $id) 
    {
        $result = PersonalAccessToken::join('users', 'personal_access_tokens.tokenable_id', '=', 'users.id')
                                    ->where('users.id', $id)
                                    ->select('users.password', 'personal_access_tokens.token')
                                    ->first();
        
        return $result;
    }
    //パスワードが正しいかを確認
    function checkPassword(string $inputPassword, string $dbpassword)
    {
        return Hash::check($inputPassword, $dbpassword);
    }
    //アクセストークンが正しいかを確認
    function checkAccessToken(string $inputAccessToken, string $dbAccessToken)
    {
        return $inputAccessToken == $dbAccessToken;
    }
    //アクセストークンを削除
    function deleteAccessToken(int $id)
    {
        PersonalAccessToken::where('tokenable_id', $id)
                            ->delete();
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\PrepareValidateData;
use Illuminate\Http\Request;
use App\Http\Requests\SignUpRequest;
use App\Mail\AuthCodeMail;
use App\Models\AuthCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use function PHPUnit\Framework\isNull;

class SignUpController extends Controller
{
    private $codeValidTime = 10;    //認証コードの有効期間
    private $codeDigits = 6;        //認証コードの桁数    

    public function __construct()
    {
        $this->middleware(PrepareValidateData::class)->only('signUp');
    }
    public function signUp(SignUpRequest $request)
    {
        $this->sendAuthMail('s21a1055@bene.fit.ac.jp', '000000');

        $userId = $this->createUserRecord($request);
        $code = $this->createAuthCode();
        $this->createAutuCodeRecord($code, $userId);
    }

    //userテーブルにレコードを作成
    function createUserRecord(SignUpRequest $request)
    {
        $isExist = $this->checkUserExist($request->email);

        if ($isExist) {
            var_dump('既に登録されています');
            die;
        }

        $userModel = new User();

        $userModel->name = $request->name;
        $userModel->email = $request->email;
        //ハッシュ化して保存
        $userModel->password = Hash::make($request->password);

        $userModel->save();

        return $userModel->id;
    }

    //ユーザが登録済みかを確認
    //登録済みならTrue
    function checkUserExist(string $email) 
    {
        $searchResult = User::where('email', $email)
                            ->whereNull('deleted_at')
                            ->first();

        $result = false;
        if ($searchResult == null) {
            $result = true;
        }

        return $result;
    }

    //認証コードを生成
    function createAuthCode() 
    {
        $result = str_pad(random_int(0, 999999), $this->codeDigits, 0, STR_PAD_LEFT);

        return $result;
    }

    //auth_codesテーブルにレコードを作成
    function createAutuCodeRecord(string $code, int $userId) 
    {
        $authCodeModel = new AuthCode();

        $expiry = new Carbon(Carbon::now());
        $expiry->addMinutes($this->codeValidTime);

        $authCodeModel->user_id = $userId;
        $authCodeModel->code = $code;
        $authCodeModel->expiry = $expiry;

        $authCodeModel->save();
    }

    //認証メールを送信
    function sendAuthMail(string $email, string $code) 
    {
        Mail::to($email)
            ->send(new AuthCodeMail($code));

        var_dump('メールを送信しました');
        die;
    }
}


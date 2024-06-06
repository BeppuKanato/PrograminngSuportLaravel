<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthRememberTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required',
            'rememberToken' => 'required|size:60',
        ];
    }

    //バリデーションに失敗した時の処理
    protected function failedValidation(Validator $validator)
    {
        $message = [
            'message' => '不正なデータです',
        ];
        throw new HttpResponseException(response()->json($message, 422));
    }
}

<?php
//バリデーションの日本語メッセージ設定
return [
    'required' => ':attributeは必須です',
    'email' => 'メールアドレス形式で入力してください',
    'min' => 
    [
        'string' => ':attributeは:min文字以上で入力してください'
    ],
    'size' => 
    [
        'string' => ':attributeは:size文字で入力してください'
    ],
    'max' => ':attributeは:max文字以下で入力してください',
    'same' => ':attributeと:otherの内容が異なっています',


    'attributes' => [
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'checkPassword' => '確認用パスワード',
        'code' => '認証コード',
        'rememberToken' => 'remember_token',
        'id' => 'ID',
    ],
];
?>
<?php

return [
    // パスワード再設定で使う標準の検証ルールを日本語化する。ルール自体は変更しない。
    'required' => ':attributeを入力してください。',
    'email' => '有効なメールアドレスを入力してください。',
    'string' => ':attributeは文字列で入力してください。',
    'min' => [
        'string' => ':attributeは:min文字以上にしてください。',
    ],
    'confirmed' => ':attributeが一致しません。',
    'attributes' => [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'token' => '再設定用トークン',
    ],
];

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('guest:store_user');
        $this->middleware('guest:admin_user');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        //postal_code整形
        if (isset($data['postal_code'])) {
            $data['postal_code'] = preg_replace('/[^0-9]/', '', $data['postal_code']);
        }
        if (isset($data['phone_number'])) {
            $data['phone_number'] = preg_replace('/[^0-9]/', '', $data['phone_number']);
        }

        $prefectureKeys = array_keys(config('commons.prefectures', []));
        $sexKeys = array_keys(config('commons.sexs', []));

        return Validator::make($data, [
            //'name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:30'],
            'nickname' => ['required', 'string', 'max:30'],
            //'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'postal_code' => [
                'required',
                'digits:7',
                'exists:zipcodes,zipcode',
            ],
            'prefecture' => ['required', Rule::in($prefectureKeys)],
            'city' => ['required', 'string', 'max:50'],
            'phone_number' => ['required', 'digits_between:10,11'],
            'sex' => ['required', Rule::in($sexKeys)],
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
        ], [
            'name.required' => '氏名を入力してください。',
            'name.string' => '氏名は文字列で入力してください。',
            'name.max' => '氏名は30文字以内で入力してください。',

            'nickname.required' => 'ニックネームを入力してください。',
            'nickname.string' => 'ニックネームは文字列で入力してください。',
            'nickname.max' => 'ニックネームは30文字以内で入力してください。',

            'email.required' => 'メールアドレスを入力してください。',
            'email.string' => 'メールアドレスは文字列で入力してください。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.max' => 'メールアドレスは255文字以内で入力してください。',
            'email.unique' => 'そのメールアドレスは既に使用されています。',

            'password.required' => 'パスワードを入力してください。',
            'password.string' => 'パスワードは文字列で入力してください。',
            'password.min' => 'パスワードは8文字以上にしてください。',
            'password.confirmed' => 'パスワードが一致しません。',

            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.digits' => '郵便番号は7桁で入力してください。',
            'postal_code.exists' => '設定できない郵便番号です。',

            'prefecture.required' => '都道府県を選択してください。',
            'prefecture.in' => '都道府県の選択が不正です。',

            'city.required' => '市区町村を入力してください。',
            'city.string' => '市区町村は文字列で入力してください。',
            'city.max' => '市区町村は50文字以内で入力してください。',

            'phone_number.required' => '電話番号を入力してください。',
            'phone_number.digits_between' => '電話番号は10桁または11桁で入力してください。',

            'sex.required' => '性別を選択してください。',
            'sex.in' => '性別の選択が不正です。',

            'birth_date.required' => '生年月日を入力してください。',
            'birth_date.date' => '生年月日は正しい日付で入力してください。',
            'birth_date.before_or_equal' => '生年月日は今日以前の日付を入力してください。',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $data['postal_code'] = preg_replace('/[^0-9]/', '', (string)$data['postal_code']);
        $data['phone_number'] = preg_replace('/[^0-9]/', '', (string)$data['phone_number']);

        if(!isset($data['email'])) {
            $data['email'] = null; //メールは一旦取得しない？
        }

        return User::create([
            'name' => $data['name'],
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'postal_code' => $data['postal_code'],
            'prefecture' => $data['prefecture'],
            'city' => $data['city'],
            'phone_number' => $data['phone_number'],
            'sex' => $data['sex'],
            'birth_date' => $data['birth_date'],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        return Validator::make($data, [
            //'name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:191'],
            'nickname' => ['required', 'string', 'max:191'],
            //'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'postal_code' => ['required', 'max:10'],
            'prefecture' => ['required'],
            'city' => ['required', 'string', 'max:50'],
            'phone_number' => ['required', 'max:50'],
            'sex' => ['required'],
            'birth_date' => ['required'],
        ], [
            'name.required' => '氏名を入力してください。',
            'nickname.required' => 'ニックネームを入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.unique' => 'そのメールアドレスは既に使用されています。',
            'password.required' => 'パスワードを入力してください。',
            'password.min' => 'パスワードは8文字以上にしてください。',
            'password.confirmed' => 'パスワードが一致しません。',
            'postal_code.required' => '郵便番号を入力してください。',
            'prefecture.required' => '都道府県を選択してください。',
            'city.required' => '市区町村を入力してください。',
            'phone_number.required' => '電話番号を入力してください。',
            'sex.required' => '性別を選択してください。',
            'birth_date.required' => '生年月日を入力してください。',
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

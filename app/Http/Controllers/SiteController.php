<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //まだログイン不要
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function list(Request $request)
    {
        $category = 'hair';
        return view('site.list', compact('category'));
    }

    public function cart(Request $request)
    {
        $category = 'hair';
        return view('site.cart', compact('category'));
    }

    public function checkout(Request $request)
    {
        $category = 'hair';
        return view('site.checkout', compact('category'));
    }

}

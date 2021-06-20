<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\DB;
class WelcomeController extends Controller
{
    public function index()
    {
        $data = array(
            'produks' => DB::table('products')->limit(10)->get(),
        );
        return view('user.welcome',$data);
    }

    public function kontak()
    {
        return view('user.kontak');
    }
}

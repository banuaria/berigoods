<?php

namespace App\Http\Controllers\API;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseController
{
    public function index(Request $request){

        $products = Product::all();
       
        // $validator = Validator::make($request->all(),[
        //     'name' => ['required','string'],
        //     'description' => ['required','string'],
        //     'price' => ['required', 'number'],
        //     'stok' => ['required', 'number'],
        //     'weight' => ['required', 'number'],
        //     'categories_id' => ['required'],

        // // ]);
        // // // dd($validator);

        // if ($validator->fails())
        // {
        //     return $this->responseError('salah', 422, $validator->errors());
        // }

        return $this->responseOk($products);
    }
}

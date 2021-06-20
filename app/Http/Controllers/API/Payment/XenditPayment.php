<?php

namespace App\Http\Controllers\Api\Payment;

use Xendit\Xendit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class XenditPayment extends Controller
{
    private $token = 'xnd_development_Dje3VamZXVYotZbi4qvlA4MUPTYJDfoFHWhTkZ9ufshgewRmKZkACPwfl3GQfWQ';

    public function getListVA(){
        
        Xendit::setApiKey('xnd_development_Dje3VamZXVYotZbi4qvlA4MUPTYJDfoFHWhTkZ9ufshgewRmKZkACPwfl3GQfWQ');

        $getVaBanks = \Xendit\VirtualAccounts::getVABanks();

        return response()->json([
            'data' => $getVaBanks
        ])->setStatusCode(200);
    }

    public function createVa(){
        Xendit::setApiKey('xnd_development_Dje3VamZXVYotZbi4qvlA4MUPTYJDfoFHWhTkZ9ufshgewRmKZkACPwfl3GQfWQ');
        $order = Order::findorfail();
        $params = [
            "external_id" => \uniqid(),
            "bank_code" => "MANDIRI",
            "name" => "banu",
        ];

        $createVa = \Xendit\VirtualAccounts::create($params);
        return response()->json([
            'data' => $createVa
        ])->setStatusCode(200);
    }
}
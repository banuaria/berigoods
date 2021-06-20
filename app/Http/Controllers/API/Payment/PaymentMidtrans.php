<?php

namespace App\Http\Controllers\Api\Payment;

use App\Order;
use Xendit\Xendit;
use GuzzleHttp\Client;
use Xendit\VirtualAccounts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentMidtrans extends Controller
{
    // private $token = 'xnd_public_development_YLHPAngJGcxI4ixF3MNBrt4CXF9ixHsokaqXMrEoX4yEFXzWRSCnvu47dobXBjp';

    public function index(){
        // $data['order'] = Order::get();
        // return view('')
    }


    public function payment(){
        
        \Midtrans\Config::$serverKey = 'SB-Mid-server-5K6Ay2EVOPkOkQrzpcvVBs8w';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $order = Order::findOrFail()->first();
        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $order->price,
            ),
            'customer_details' => array(
                'first_name' => 'budi',
                'last_name' => 'pratama',
                'email' => 'budi.pra@example.com',
                'phone' => '08111222333',
            ),
        );
         
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return response()->json(['token' =>$snapToken ]);
    }

   public function virtualaccount(Request $request)
   {
       $client = New Client();
       $response = $client->post('https://api.sandbox.midtrans.com/v2/charge',
        [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Basic 5K6Ay2EVOPkOkQrzpcvVBs8w',
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'payment_type' => 'bank_transfer',
                'transaction_details' => [
                    'order_id' => 'va-midtrans-'.time(),
                    'gross_amount'=> $request->price
                ],
                'bank_transfer' => [
                    'bank' => $request-> bank
                ]
            ])
                ]);
        $data = json_encode($response->getBody());
        return response()->json($data);
   }
}

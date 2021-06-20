<?php

namespace App\Http\Controllers\user;

use App\Order;
use App\Rekening;
use Midtrans\Snap;
use App\Detailorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Midtrans\Transaction;

class OrderController extends Controller
{



    public function index()
    {
        //menampilkan semua data pesanan
        $user_id = Auth::user()->id;
        
        $order = DB::table('order')
                    ->join('status_order','status_order.id','=','order.status_order_id')
                    ->select('order.*','status_order.name')
                    ->where('order.status_order_id',1)
                    ->where('order.user_id',$user_id)->get();
        $dicek = DB::table('order')
                    ->join('status_order','status_order.id','=','order.status_order_id')
                    ->select('order.*','status_order.name')
                    ->where('order.status_order_id','!=',1)
                    ->Where('order.status_order_id','!=',5)
                    ->Where('order.status_order_id','!=',6)
                    ->where('order.user_id',$user_id)->get();

        $histori = DB::table('order')
        ->join('status_order','status_order.id','=','order.status_order_id')
        ->select('order.*','status_order.name')
        ->where('order.status_order_id','!=',1)
        ->Where('order.status_order_id','!=',2)
        ->Where('order.status_order_id','!=',3)
        ->Where('order.status_order_id','!=',4)
        ->where('order.user_id',$user_id)->get();

        \Midtrans\Config::$serverKey = 'SB-Mid-server-5K6Ay2EVOPkOkQrzpcvVBs8w';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;


        // $status = \Midtrans\Transaction::status("9744ae7a-226f-48e1-9e78-e0e6e49b42b4");
        // dd($status);
        
        $data = array(
            'order' => $order,
            'dicek' => $dicek,
            'histori'=> $histori,
        );
        return view('user.order.order',$data);
    }

    public function detail($id)
    {
        //function menampilkan detail order
        $detail_order = DB::table('detail_order')
        ->join('products','products.id','=','detail_order.product_id')
        ->join('order','order.id','=','detail_order.order_id')
        ->select('products.name as nama_produk','products.image','detail_order.*','products.price','order.*')
        ->where('detail_order.order_id',$id)
        ->get();
        $order = DB::table('order')
        ->join('users','users.id','=','order.user_id')
        ->join('status_order','status_order.id','=','order.status_order_id')
        ->select('order.*','users.name as nama_pelanggan','status_order.name as status')
        ->where('order.id',$id)
        ->first();
        $data = array(
        'detail' => $detail_order,
        'order'  => $order
        );
        return view('user.order.detail',$data);
    }

    public function sukses()
    {
        //menampilkan view terimakasih jika order berhasil dibuat
        return view('user.terimakasih');
    }


    public function pesananditerima($id)
    {

        //function untuk menerima pesanan
        $order = Order::findOrFail($id);
        $order->status_order_id = 5;
        $order->save();

        return redirect()->route('user.order');

    }

    public function pesanandibatalkan($id)
    {
        //function untuk membatalkan pesanan
        $order = Order::findOrFail($id);
        $order->status_order_id = 6;
        $order->save();

        return redirect()->route('user.order');

    }

    public function simpan(Request $request)
    {
        //untuk menyimpan pesanan ke table order
        $cek_invoice = DB::table('order')->where('invoice',$request->invoice)->count();
        if($cek_invoice < 1){
            $userid = Auth::user()->id;
            //jika pelanggan memilih metode cod maka insert data yang ini

            Order::create([
                'invoice' => $request->invoice,
                'user_id' => $userid,
                'subtotal'=> $request->subtotal,
                'status_order_id' => 1,
                'ongkir' => $request->ongkir,
                'no_hp' => $request->no_hp,
                'pesan' => $request->pesan
            ]);

        $order = DB::table('order')->where('invoice',$request->invoice)->first();
        
        $barang = DB::table('keranjang')->where('user_id',$userid)->get();
        //lalu masukan barang2 yang dibeli ke table detail order
        foreach($barang as $brg){
            Detailorder::create([
                'order_id' => $order->id,
                'product_id' => $brg->products_id,
                'qty' => $brg->qty,
            ]);
        }

        //lalu hapus data produk pada keranjang pembeli
        DB::table('keranjang')->where('user_id',$userid)->delete();
        return redirect()->route('user.order.sukses');
        }else{
            return redirect()->route('user.keranjang');
        }
        // dd($request);

    }

    public function payment($id_barang){
         
        \Midtrans\Config::$serverKey = 'SB-Mid-server-5K6Ay2EVOPkOkQrzpcvVBs8w';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        
        
         $stuff = Order::where('id', $id_barang)->first();

         $params = [
            'transaction_details' => [
                'order_id' => $stuff->invoice,
                'gross_amount' => $stuff->subtotal,
            ],
            'customer_details'=> [
                'first_name' => 'yth, ',
                'last_name' => Auth::user()->name,
                'phone' => $stuff->no_hp,
                'email' => Auth::user()->email,
                    ],
            
                ];
        
        
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return response()->json(['token'=> $snapToken]);

        // $order = Order::findOrFail($id);

        // $params = [
        //     'enable_payments' => \App\Payment::PAYMENT_CHANNELS,
        //     'transaction_details' => [
        //         'order_id' => $order->invoice,
        //         'gross_amount'=> $order->subtotal,
        //     ],
        //     'customer_details'=> [
        //         'first_name' => 'yth, ',
        //         'last_name' => $order->name,
        //         'phone' => $order->no_hp,
        //         'email' => Auth::user()->email,
        //     ],
        //     'expiry'=> [
        //         'start_time' => date('Y-m-d H:i:s T'),
        //         'unit' => \App\Payment::EXPIRY_UNIT,
        //         'duration' => \App\Payment::EXPIRY_DURATION,
        //     ],
        // ];

        // $snap = \Midtrans\Snap::createTransaction($params);
		
		// if ($snap->token) {
		// 	$order->payment_token = $snap->token;
		// 	$order->payment_url = $snap->redirect_url;
        //     $order->status_order_id  = 2;
		// 	$order->save();
		// }
        // $this->response['snapToken'] = $snapToken;
        // return response()->json($this->response);
    }
    public function finish(Request $request)
    {
        \Midtrans\Config::$serverKey = 'SB-Mid-server-5K6Ay2EVOPkOkQrzpcvVBs8w';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $status = \Midtrans\Transaction::status($request->get('id'));
        // $status = \Midtrans\Transaction::status("9744ae7a-226f-48e1-9e78-e0e6e49b42b4");
        // dd($status);
        $stuff = Order::where('invoice', $status->order_id)->first();
        if($status->transaction_status == "settlement"){
            $stuff->status_order_id = 3;
            $stuff->save();
            Auth::loginUsingId($stuff->user_id);
            return redirect()->route('user.order');
        }
    }

    public function notificationHandler($id_barang){

        $stuff = Order::where('id', $id_barang)->first();
        $notif = new \Midtrans\Notification();
        $transaction = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        error_log("Order ID $notif->order_id: "."transaction status = $transaction, fraud staus = $fraud");

        if ($transaction == 'seattlement') {
            $stuff->status_order_id = 5;
            $stuff->save();
            return redirect()->route('user.order');
        }
        // else if ($transaction == 'pending') {
        //    return redirect()->route('user.order');
        // }
        // else if ($transaction == 'failure') {
        //     return redirect()->route('user.order');
        // }
    }
}

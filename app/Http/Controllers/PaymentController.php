<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
// use Midtrans\Transaction;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PaymentController extends Controller
{
    public function getSnapToken(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');



        $orderId = uniqid();
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $request->amount,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        // Save transaction to database
        Transaction::create([
            'order_id' => $orderId,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        return response()->json(['snap_token' => $snapToken]);
    }

    public function processPayment(Request $request)
    {
        $json = json_decode($request->json, true);

        // Update transaction status
        $transaction = Transaction::where('order_id', $json['order_id'])->first();
        if ($transaction) {
            $transaction->update([
                'status' => $json['transaction_status'],
                'payment_response' => $request->json,
            ]);
        }

        return redirect()->route('home')->with('status', 'Payment ' . $json['transaction_status']);
    }
}

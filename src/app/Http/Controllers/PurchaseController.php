<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function showPurchaseForm($item_id){
        $item = Product::with('productCondition')->findOrFail($item_id);
        $payment_methods = PaymentMethod::all();
        $user = Auth::user();

        // 既に仮保存されている購入レコードがあるか検索
        $purchase = Purchase::where('product_id', $item->id)
        ->whereNull('completed_at') //確定されていない。
        ->first();

        if (!$purchase) {
            // 仮保存レコードを作成
            $purchase = Purchase::create([
                'buyer_id' => $user->id,
                'product_id' => $item->id,
                'purchase_price' => $item->price,
                'postal_code' => $user->postal_code,
                'address' => $user->address,
                'building' => $user->building,
                // この時点ではpayment_method_idは未設定
            ]);
        }

        return view('purchases.create', compact('item', 'user', 'payment_methods', 'purchase'));
    }

    public function purchase(PurchaseRequest $request, $item_id){
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $user = Auth::user();

        $purchase = Purchase::where('product_id', $item_id)
            ->where('buyer_id', $user->id)
            ->whereNull('completed_at')
            ->firstOrFail();

        $data = $request->validated();

        $purchase->update([
            'postal_code' => $data['postal_code'],
            'address' => $data['address'],
            'building' => $data['building'],
            'payment_method_id' => $data['payment_method'],
            'completed_at' => now(),
        ]);

        $method = PaymentMethod::find($data['payment_method']);

        $paymentMethodTypes = [];
        if ($data['payment_method'] === '2') {
            $paymentMethodTypes = ['card'];
        } elseif ($data['payment_method'] === '1') {
            $paymentMethodTypes = ['konbini'];
        } else {
            abort(400, '対応していない支払い方法です。');
        }

        $sessionData = [
            'metadata' => [
                'purchase_id' => $purchase->id,
            ],
            'payment_intent_data' => [
                'metadata' => [
                    'purchase_id' => $purchase->id,
                ],
            ],
            'client_reference_id' => $purchase->id,
            'payment_method_types' => $paymentMethodTypes,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $purchase->product->name],
                    'unit_amount' => $purchase->product->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success') . '?purchase_id=' . $purchase->id,
            'cancel_url' => route('payment.cancel'),
        ];

        if ($method->name === 'コンビニ支払い') {
            $sessionData['billing_address_collection'] = 'required';
        }

        $session = \Stripe\Checkout\Session::create($sessionData);

        return redirect($session->url);
    }

    public function showAddressForm($item_id){
        $purchase = Purchase::where('product_id', $item_id)->whereNull('completed_at')->firstOrFail();
        return view('purchases.address', compact('purchase', 'item_id'));
    }

    public function updateAddress(AddressRequest $request, $item_id){
        $data = $request->validated();
        $purchase = Purchase::where('product_id', $item_id)->whereNull('completed_at')->firstOrFail();

        $purchase->postal_code = $data['postal_code'];
        $purchase->address = $data['address'];
        $purchase->building = $data['building'];

        $purchase->save();

        return redirect()->route('purchase.show', $purchase->product_id);
    }

    public function success() {
        return view('purchases.checkout')->with('message', '支払いが成功しました。');
    }

    public function cancel() {
        return view('purchases.checkout')->with('message', '支払いがキャンセルされました。');
    }

    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $event = json_decode($payload);

        switch ($event->type) {
            case 'checkout.session.completed':
                $purchaseId = $event->data->object->client_reference_id ?? 'null';
                Log::info("Stripe webhook: チェックアウト完了 - purchase_id: {$purchaseId}");
                break;

            case 'payment_intent.succeeded':
                $intent = $event->data->object;
                $purchaseId = $intent->metadata->purchase_id ?? null;

                if (!$purchaseId) {
                    Log::warning("payment_intent に purchase_id が含まれていません");
                    break;
                }

                $purchase = Purchase::find($purchaseId);
                if (!$purchase) {
                    Log::warning("purchase_id {$purchaseId} が見つかりません");
                    break;
                }

                if ($purchase->paid_at) {
                    Log::info("purchase_id {$purchaseId} はすでに完了済み");
                    break;
                }

                $purchase->update([
                    'paid_at' => now(),
                ]);

                Log::info("purchase_id {$purchaseId} を paid として更新");
                break;

            case 'payment_intent.payment_failed':
                $purchaseId = $event->data->object->metadata->purchase_id ?? 'null';
                Log::info("Stripe webhook: 支払い失敗 - purchase_id: {$purchaseId}");
                break;
        }

        //webhookに届いたことをレスポンス
        return response()->json(['status' => 'ok']);
    }
}


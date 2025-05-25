<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;

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
                // payment_method_idはまだ未設定でも良い
            ]);
        }

        return view('purchases.create', compact('item', 'user', 'payment_methods', 'purchase'));
    }

    public function purchase(PurchaseRequest $request, $item_id){
        $user = Auth::user();

        // 対象商品の未完了購入で、かつ現在のユーザーが購入者であるものを取得
        $purchase = Purchase::where('product_id', $item_id)
        ->where('buyer_id', $user->id)
        ->whereNull('completed_at')
        ->firstOrFail();

        $data = $request->validated();

        // 該当購入レコードを更新して、購入完了にする
        $purchase->update([
            'postal_code' => $data['postal_code'],
            'address' => $data['address'],
            'building' => $data['building'],
            'payment_method_id' => $data['payment_method'],
            'completed_at' => now(),
        ]);

        return redirect('/mypage?tab=buy');
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
}

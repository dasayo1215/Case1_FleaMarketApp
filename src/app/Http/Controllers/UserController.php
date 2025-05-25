<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateProfileRequest;

class UserController extends Controller
{
    public function showProfile(Request $request){
        $tab = $request->query('tab');

        if($tab === 'buy'){
            // /mypage?tab=buy
            return $this->purchasedItems();
        }else{
            // /mypage?tab=sell
            return $this->listedItems();
        }
    }

    public function purchasedItems(){
        // 購入した商品を表示させる
        $user = Auth::user();
        $items = Product::whereHas('purchase', function($query) use ($user) {
            $query->where('buyer_id', $user->id)->whereNotNull('completed_at');
        })->with('purchase')->latest()->get();
        return view('users.show', compact('items', 'user'));
    }

    public function ListedItems(){
        // 出品した商品を表示させる
        $user = Auth::user();
        $items = Product::where('seller_id', $user->id)
        ->with('purchase')
        ->latest()
        ->get();
        return view('users.show', compact('items', 'user'));
    }

    public function editProfile(){
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    public function updateProfile(UpdateProfileRequest $request){
        $data = $request->validated();
        $user = Auth::user();

        // 画像処理（アップロードされていれば保存）
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // 古い画像があれば削除
            if ($user->image_filename) {
                Storage::disk('public')->delete('users/' . $user->image_filename);
            }
    
            // リネーム: userID_タイムスタンプ.拡張子
            $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('users', $filename, 'public');
    
            // カラムに保存
            $data['image_filename'] = $filename;
        }

        $user->update($data);
        return redirect('/');
    }
}


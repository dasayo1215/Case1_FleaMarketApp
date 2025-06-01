<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;

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

    public function updateProfile(AddressRequest $request){
        $data = $request->validated();
        $user = Auth::user();

        // 画像処理（アップロードされていれば保存）
        if ($path = session('profile_uploaded_image_path')) {
            // 古い画像があれば削除
            if ($user->image_filename) {
                Storage::disk('public')->delete('users/' . $user->image_filename);
            }
    
            // セッションのパスは例えば tmp/xxxx.png の形なので、ファイルを移動して名前を変える処理が必要かも
            $filename = $user->id . '_' . time() . '.' . pathinfo($path, PATHINFO_EXTENSION);

            // tmp から users フォルダへ移動
            Storage::disk('public')->move($path, 'users/' . $filename);

            $data['image_filename'] = $filename;

            session()->forget('profile_uploaded_image_path');
        }

        $user->update($data);
        return redirect('/');
    }

    public function uploadImage(ProfileRequest $request) {
        // 保存先: storage/app/public/tmp
        $path = $request->file('image')->store('tmp', 'public');

        // セッションに保存
        session(['profile_uploaded_image_path' => $path]);

        return redirect('/mypage/profile')->withInput(); // 他の入力値も復元
    }
}


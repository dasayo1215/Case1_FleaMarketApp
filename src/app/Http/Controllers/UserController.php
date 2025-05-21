<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showProfile(Request $request){
        $tab = $request->query('tab');

        if($tab === 'buy'){
            // /mypage?tab=buy
            return $this->purchasedItems();
        }elseif($tab === 'sell'){
            // /mypage?tab=sell
            return $this->listedItems();
        }else{
            return view('users.show');
        }
    }

    public function purchasedItems(){
        // 購入した商品を表示させる
        return view('users.show');
    }

    public function ListedItems(){
        // 出品した商品を表示させる
        return view('users.show');
    }

    public function editProfile(){
        return view('users.edit');
    }

    public function updateProfile(){
        //
    }
}


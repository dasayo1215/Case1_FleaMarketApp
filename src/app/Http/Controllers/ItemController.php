<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request){
        $tab = $request->query('tab');

        if($tab === 'mylist'){
            // /?tab=mylist
            return $this->mylist();
        }else{
            return view('items.index');
        }
    }

    public function mylist(){
        // いいねした商品を表示させる
        return view('items.index');
    }

    public function show(){
        return view('items.show');
    }

    public function storeComment(){
        //
    }

    public function toggleLike(){
        //
    }

    public function showSellForm(){
        return view('items.create');
    }

    public function store(){
        //
    }
}

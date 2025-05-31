<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ProductCondition;
use App\Models\Product;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function index(Request $request){
        $tab = $request->query('tab');
        $keyword = $request->query('keyword');

        if($tab === 'mylist'){
            return $this->mylist($request);
        }

        $query = Product::with('purchase');

        // ログインしている場合は自分が出品した商品を除外
        if (Auth::check()) {
            $user = Auth::user();
            $query->where('seller_id', '!=', $user->id);
        }

        if(!empty($keyword)){
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        $items = $query->latest()->get();

        return view('items.index', compact('items', 'keyword', 'tab'));
    }

    public function mylist(Request $request)
    {
        $items = [];
        $keyword = $request->query('keyword');

        if (Auth::check()) {
            $user = Auth::user();
            $items = Product::whereHas('likes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->when($keyword, function ($query, $keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            })
            ->with('likes')->latest()->get();
        }

        $tab='mylist';

        return view('items.index', compact('items', 'keyword', 'tab'));
    }

    public function show($item_id){
        $item = Product::with(['categories', 'productCondition', 'purchase'])->findOrFail($item_id);
        $user = Auth::user();
        return view('items.show', compact('item', 'user'));
    }

    public function storeComment(CommentRequest $request, $item_id){
        $request->validated();
    
        Comment::create([
            'user_id' => Auth::id(),
            'product_id' => $item_id,
            'comment' => $request->comment,
        ]);
    
        return redirect()->back();
    }

    public function toggleLike($item_id){
        $user = Auth::user();
        $like = Like::where('user_id', $user->id)
                    ->where('product_id', $item_id)
                    ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'product_id' => $item_id,
            ]);
        }

        return redirect()->back();
    }

    public function showSellForm(){
        $categories = Category::all();
        $conditions = ProductCondition::all();
        return view('items.create', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request){
        $validated = $request->validated();

        // ログインユーザーIDを取得
        $sellerId = auth()->id();

        // productsテーブルに保存
        $product = new Product();
        $product->name = $validated['name'];
        $product->brand = $validated['brand'] ?? null;
        $product->description = $validated['description'];
        $product->price = str_replace(',', '', $validated['price']); // コンマ除去
        $product->product_condition_id = $validated['product_condition_id'];
        $product->seller_id = $sellerId;

        // 画像があるかチェックし、先にファイル名を決定・保存
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $product->image_filename = ''; // 仮の値（NOT NULL 対策）
            $product->save();

            // 保存後のIDを使ってファイル名を作成
            $filename = $product->id . '_' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('products', $filename, 'public');

            // ファイル名を改めて保存
            $product->image_filename = $filename;
            $product->save();
        }

        // カテゴリーは多対多なのでattach
        $product->categories()->sync($validated['category_id']);

        return redirect('/mypage');
    }
}

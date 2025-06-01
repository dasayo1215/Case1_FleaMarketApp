<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\UploadImageRequest;
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

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();
    
        // ログインユーザーID
        $sellerId = auth()->id();
    
        // 新しい Product インスタンス生成
        $product = new Product();
        $product->name = $validated['name'];
        $product->brand = $validated['brand'] ?? null;
        $product->description = $validated['description'];
        $product->price = str_replace(',', '', $validated['price']);
        $product->product_condition_id = $validated['product_condition_id'];
        $product->seller_id = $sellerId;
    
        // 仮に空で保存（画像ファイル名にIDを使いたいため）
        $product->image_filename = '';
        $product->save();
    
        // 画像の一時パスから正式ファイル名に変更して移動
        $tmpPath = $validated['sell_uploaded_image_path'];
        if ($tmpPath && \Storage::disk('public')->exists($tmpPath)) {
            // 拡張子取得
            $extension = pathinfo($tmpPath, PATHINFO_EXTENSION);
    
            // ファイル名を決定
            $filename = $product->id . '_' . now()->format('YmdHis') . '.' . $extension;
    
            // ファイルを tmp → products に移動
            \Storage::disk('public')->move($tmpPath, 'products/' . $filename);
    
            // ファイル名を保存
            $product->image_filename = $filename;
            $product->save();
        }
        session()->forget(['sell_uploaded_image_path']);
    
        // カテゴリーを関連付け
        $product->categories()->sync($validated['category_id']);
    
        return redirect('/mypage');
    }
    

    public function uploadImage(UploadImageRequest $request) {
        // 保存先: storage/app/public/tmp
        $path = $request->file('image')->store('tmp', 'public');

        // セッションに保存
        session(['sell_uploaded_image_path' => $path]);

        return redirect('/sell')->withInput(); // 他の入力値も復元
    }

}

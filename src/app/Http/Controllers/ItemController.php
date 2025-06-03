<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\UploadImageRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ItemCondition;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request) {
        $tab = $request->query('tab');
        $keyword = $request->query('keyword');

        if($tab === 'mylist'){
            return $this->mylist($request);
        }

        $query = Item::with('purchase');

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

    public function mylist(Request $request) {
        $items = [];
        $keyword = $request->query('keyword');

        if (Auth::check()) {
            $user = Auth::user();

            $items = $user->likedItems()
                ->when($keyword, function ($query, $keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                })
                ->orderBy('pivot_created_at', 'desc') // いいね順に並び替え
                ->get();
        }

        $tab='mylist';

        return view('items.index', compact('items', 'keyword', 'tab'));
    }

    public function show($itemId){
        $item = Item::with(['categories', 'itemCondition', 'purchase'])->findOrFail($itemId);
        $user = Auth::user();
        return view('items.show', compact('item', 'user'));
    }

    public function storeComment(CommentRequest $request, $itemId){
        $request->validated();

        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $itemId,
            'comment' => $request->comment,
        ]);

        return redirect()->back();
    }

    public function toggleLike($itemId){
        $user = Auth::user();
        $like = Like::where('user_id', $user->id)
                    ->where('item_id', $itemId)
                    ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'item_id' => $itemId,
            ]);
        }

        return redirect()->back();
    }

    public function showSellForm(){
        $categories = Category::all();
        $conditions = ItemCondition::all();
        return view('items.create', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();
        $sellerId = auth()->id();

        // 新しい Item インスタンス生成
        $item = new Item();
        $item->name = $validated['name'];
        $item->brand = $validated['brand'] ?? null;
        $item->description = $validated['description'];
        $item->price = str_replace(',', '', $validated['price']);
        $item->item_condition_id = $validated['item_condition_id'];
        $item->seller_id = $sellerId;

        // 仮に空で保存（画像ファイル名にIDを使いたいため）
        $item->image_filename = '';
        $item->save();

        // 画像の一時パスから正式ファイル名に変更して移動
        $tmpPath = $validated['sell_uploaded_image_path'];
        if ($tmpPath && \Storage::disk('public')->exists($tmpPath)) {
            $extension = pathinfo($tmpPath, PATHINFO_EXTENSION);
            $filename = $item->id . '_' . now()->format('YmdHis') . '.' . $extension;
            \Storage::disk('public')->move($tmpPath, 'items/' . $filename);
            $item->image_filename = $filename;
            $item->save();
        }

        $item->categories()->sync($validated['category_id']);

        session()->forget(['sell_uploaded_image_path']);

        return redirect('/mypage');
    }

    public function uploadImage(UploadImageRequest $request) {
        $path = $request->file('image')->store('tmp', 'public');

        // セッションに保存
        session(['sell_uploaded_image_path' => $path]);
        session()->flashInput($request->except('image'));

        return redirect()->route('sell');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // 商品一覧画面で、おすすめ・マイリストのタブの切り替えとキーワード検索に対応しています
    public function index(Request $request)
    {
        $tab = $request->query("tab");

        if ($tab === "mylist") {
            //マイリスト表示はログイン必須の為、未ログインの場合はログイン画面に移動します
            if (!Auth::check()) {

                return redirect()->route("login");
            }

            /** @var \App\Models\User $user */
            $user = Auth::user();
            $query = $user->favorite_items();

        } else {

            $query = Item::query();
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
            });
        }

        $items = $query->get();

        // ログインしている場合、自分以外のIDの商品だけを取得し、未認証の場合は全件取得します
        $items = Item::where('user_id',  '!=', auth()->id())->get();

        return view('item.list', compact('items', "tab", "items"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view("item.sell", compact('categories', 'conditions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExhibitionRequest $request)
    {
        $path = null;

        if($request->hasfile("image")){
            $path = $request->file("image")->store("image", "public");
        }

        $item = Item::create([
            // 誰の出品なのか
            "user_id" => Auth::id(),
            "name" => $request->name,
            "brand_name" => $request->brand_name,
            "description" => $request->description,
            "price" => $request->price,
            "image" => $path,
            "condition_id" => $request->condition_id,
        ]);

        // カテゴリーは中間テーブルを作成している為、Itemモデルで定義したカテゴリーのリレーションを呼び出し保存をします
        if ($request->has('category_ids')) {
            $item->categories()->attach($request->category_ids);
        }

        return redirect()->route('mypage.index')->with('message', '商品を出品しました！');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show($item_id)
    {
        $item = Item::with("comments.user")->findOrFail($item_id);

        return view("item.show", compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }
}

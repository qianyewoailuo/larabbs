<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;

class CategoriesController extends Controller
{


    // 分类展示
    public function show(Request $request,Category $category)
    {
        // 读取当前分类ID 关联的话题 并以20条进行分页
        // $topics = Topic::where('category_id',$category->id)->with('category','user')->paginate(20);
        // 增加自定义的排序方法withOrder
        $topics = Topic::where('category_id',$category->id)
                            ->withOrder($request->order)
                            ->paginate(20);
        // 传参变量话题和分类到模板中
        return view('topics.index',compact('topics','category'));
    }
}

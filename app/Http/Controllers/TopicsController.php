<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Handlers\ImageUploadHandler;
use App\Models\User;

class TopicsController extends Controller
{
    public function __construct()
    {
        // 登录用户验证
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    // 话题创建与编辑中上传图片
    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'success'   => false,
            'msg'       => '上传失败!',
            'file_path' => ''
        ];
        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);
            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }
        return $data;
    }


    // 话题列表页
    public function index(Request $request, Topic $topic,User $user)
    {
        // 所有的 ORM 关联数据读取都会触及 N+1 的问题
        // 所以记得在遇到关联模型数据读取时使用 with()方法预加载关联属性进行调优 优化效率提高至少1/3
        // $topics = Topic::with('user','category')->paginate(30);
        // 增加自定义的排序方法withOrder
        $topics = Topic::withOrder($request->order)->paginate(20);
        // 获取活跃用户
        $active_users = $user->getActiveUsers();
        // 测试活跃用户
        // dd($active_users);

        return view('topics.index', compact('topics','active_users'));
    }

    public function show(Request $request ,Topic $topic)
    {
        // 当slug参数存在且访问了非优化链接时将URL重定向到优化链接
        if (!empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }


        return view('topics.show', compact('topic'));
    }

    // 话题创建
    public function create(Topic $topic)
    {
        // 获取分类信息
        $categories = Category::all();
        // 传递话题信息与分类信息
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }
    // 话题创建保存
    public function store(TopicRequest $request, Topic $topic)
    {
        // fill方法会将传参的键值数组填充到模型的属性中
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        // 使用save方法是因为模型限制了user_id的写入
        $topic->save();
        // 创建成功跳转
        // return redirect()->route('topics.show', $topic->id)->with('success', '帖子创建成功!');
        // slug链接优化
        return redirect()->to($topic->link())->with('success', '成功创建话题！');
    }

    public function edit(Topic $topic)
    {
        // 更新策略授权认证
        $this->authorize('update', $topic);
        // 分类信息获取
        $categories = Category::all();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        // 更新策略授权认证
        $this->authorize('update', $topic);
        $topic->update($request->all());

        // return redirect()->route('topics.show', $topic->id)->with('success', '更新成功');
        // 优化链接
        return redirect()->to($topic->link())->with('success', '更新成功');
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return redirect()->route('topics.index')->with('danger', '删除成功');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
// 这里引用 表单请求验证(FormRequest) 能处理更为复杂的验证逻辑,更加适用于大型程序。
// 创建命令 $ php artisan make:request UserRequest
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    // 当前show方法的参数使用了 [隐性路由绑定] 是是『约定优于配置』设计范式的体现
    // 具体解析参考 https://learnku.com/courses/laravel-intermediate-training/5.7/personal-page/2610#889027 md文档中的 创建控制器 部分

    // 个人中心显示
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }
    // 用户资料编辑显示
    public function edit(User $user)
    {
        return view('users.edit',compact('user'));
    }
    // 用户资料编辑更新
    public function update(User $user,UserRequest $userRequest,ImageUploadHandler $uploader)
    {
        // 测试是否返回了avatar对象
        // 第一次测试为字符串 因为忘记了在表单中增加上传文件的声明
        // 即 enctype="multipart/form-data"  重新填入即可
        // dd($userRequest->avatar);

        // 记得要将新增的introduction字段添加到filladle中
        // $user->update($userRequest->all());

        // 获取数据并验证
        $data = $userRequest->all();
        // 获取头像并上传
        if ($userRequest->avatar) {
            $result = $uploader->save($userRequest->avatar,'avatars',$user->id,416);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }
        // 更新用户信息
        $user->update($data);
        // with方法是请求门面提供的闪存设置方法
        return redirect()->route('users.show',Auth::user())->with('success','个人资料更新成功!');
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // 权限验证
        // 关于用户授权，后面会使用更具扩展性的方案，
        // 此处我们改为 return true,即暂时所有权限都通过即可。
        return true;
        // return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // 验证规则
        return [
            // unique:users,name,Auth::id  表示在默认数据库中的users表中检测字段name必须唯一,除了当前id为Auth::id()的用户
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,'.Auth::id(),
            'email' => 'required|email',
            'introduction'  =>  'max:100'
        ];
    }
    // 自定义验证错误消息
    public function messages()
    {
        return [
            'name.unique' => '用户名已存在，请重新填写',
            'name.regex' => '用户名只支持英文、数字、横杠和下划线。',
            'name.between' => '用户名必须介于 3 - 25 个字符之间。',
            'name.required' => '用户名不能为空。',
        ];
    }
}

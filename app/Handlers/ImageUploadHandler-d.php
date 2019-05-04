<?php

namespace App\Handlers;

use Intervention\Image\Facades\Image;

/*
 * @Description: 图片上传核心类
 * @Author: luo
 * @email: qianyewoailuo@126.com
 * @Date: 2019-05-04 23:47:33
 * @LastEditTime: 2019-05-05 00:52:12
 */

class ImageUploadHandler
{
    // 可允许图片上传类型
    protected $allowed_ext = ['png','jpg','gif','jpeg'];
    // 图片保存
    public function save($file,$floder,$file_prefix)
    {
        // 构建存储文件夹规则 例如 uploads/images/avatars/201905/04/
        // 文件夹切割能让查找效率更高
        $folder_name = "uploads/images/$floder/".date("Ym/d",time());

        // 文件具体存储的真实路径 辅助方法 public_path() 能获取当前项目public目录物理路径
        $upload_path = public_path().'/'.$folder_name;

        // 获取文件后缀名
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名
        $filename = $file_prefix.'_'.time().'_'.str_random(10).'.'.$extension;

        // 如果上传的不是图片终止操作
        if (! in_array($extension,$this->allowed_ext)){
            return false;
        }

        // 移动图片
        $file->move($upload_path,$filename);

        return [
            'path' => config('app.url')."/$folder_name/$filename",
        ];

    }

}
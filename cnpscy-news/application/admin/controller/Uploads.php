<?php

namespace app\admin\controller;

use think\Request;

class Uploads extends Common
{
    public function initialize()
    {
        parent::initialize();
    }

    public function files($file_name = '/', $old_width = 200, $old_height = 200)
    {
        $file = request()->file('file');
        if (empty($file)) return self::apiAdminReturn(['msg' => '图片丢失！']);
        $info = $file->move('./uploads');
        if (!empty($info)) {
            $img_url = '/uploads/' . $info->getSaveName();
            //图片压缩
            $image = \think\Image::open(ROOT_PATH . $img_url);
            if ($image->width() / 4 < $old_width) {
                $width = $old_width;
                $height = $image->width() / $old_width * $old_height;
            } else {
                $width = $image->width() / 4;
                $height = $image->height() / 4;
            }
            // 压缩图片并替换
            $image->thumb($width, $height)->save(ROOT_PATH . $img_url);
            return self::apiAdminReturn(['msg' => '图片上传成功！', 'file_url' => $img_url, 'status' => 1]);
        } else return self::apiAdminReturn(['msg' => '图片丢失！']);
    }
}

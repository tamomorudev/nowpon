<?php

namespace App\Services;

use Illuminate\Http\Request;

class ImageService
{
    public function upload(Request $request, $dir, $key = 'images')
    {
        // 画像が存在しない場合
        if (!$request->hasFile($key)) {
            return '';
        }

        $file = $request->file($key);

        // サイズチェック（1GB）
        $maxSize = 1000 * 1024 * 1024; // 1GB
        if ($file->getSize() > $maxSize) {
            return [
                'error' => 'ファイルサイズが1GBを超えています。',
                'path'  => '',
            ];
        }

        // MIME チェック
        if (strpos($file->getMimeType(), 'image') === false) {
            return [
                'error' => '画像ファイルではありません。',
                'path'  => '',
            ];
        }

        // 保存
        $path = $file->store($dir, 'pub_images');

        return [
            'error' => null,
            'path'  => $path,
        ];
    }
}

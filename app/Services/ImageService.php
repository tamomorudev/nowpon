<?php

namespace App\Services;

use Illuminate\Http\Request;

class ImageService
{
    public function upload(Request $request, $dir, $key = 'images')
    {
        // 画像が存在しない場合
        if (!$request->hasFile($key)) {
            return [
                'error' => '画像は必須登録です。',
                'path'  => '',
            ];
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

    public function uploadCouponImages($request)
    {
        $coupon_image_array = [];
        $imgIndex = 0;
        $image_keys = ['images', 'images_2', 'images_3', 'images_4', 'images_5'];
        $maxSize = 1000 * 1024 * 1024; // 1GB

        foreach ($image_keys as $key) {
            if (!isset($request[$key]) || !$request[$key]) {
                continue;
            }

            $file = $request[$key];
            $fileSize = $file->getSize();

            // サイズチェック
            if ($fileSize > $maxSize) {
                return [
                    'error' => true,
                    'message' => "{$key} のファイルサイズが制限を超えています。"
                ];
            }

            // 画像チェック
            if (strpos($file->getMimeType(), 'image') === false) {
                continue;
            }

            // 保存
            $path = $file->store('coupon_image', 'pub_images');

            // フィールド名生成
            $fieldName = $imgIndex === 0 ? 'img_url' : 'img_url_' . ($imgIndex + 1);
            $coupon_image_array[$fieldName] = $path;

            $imgIndex++;
        }

        return [
            'error' => false,
            'data' => $coupon_image_array
        ];
    }

}

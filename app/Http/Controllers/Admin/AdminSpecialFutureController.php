<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use App\Models\Coupons;
use App\Models\Stores;
use App\Models\StoreServices;
use App\Models\SpecialFutures;

class AdminSpecialFutureController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * coupon pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $special_futures = SpecialFutures::select()->where('delete_flg', 0)->orderBy('created_at', 'DESC')->paginate(50); //特集情報
        $stores = Stores::select()->get(); //stores情報
        return view('admin.special_future.index', compact('user', 'stores', 'special_futures'));
    }

    public function create(Request $request)
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $stores = Stores::select()->get(); //stores情報
        $request = $request->all();
        $defaultweeks = [];

        if (isset($request['name'])) {
            $validated_data = Validator::make($request, [
                'name' => 'required|max:190',
                'outline' => 'required|max:190',
                'detail' => 'required',
                'start_date' => 'required',
                'end_date' => 'required'
            ]);

            if ($validated_data->fails()) {
                return redirect()->route('admin.special_future.create')
                    ->withErrors($validated_data)
                    ->withInput();
            }

            $request['detail'] = $this->convertEmbeddedDetailImages($request['detail']);

            if ($this->hasEmbeddedDetailImages($request['detail'])) {
                return redirect()->route('admin.special_future.create')
                    ->withErrors(['detail' => '本文内の画像はアップロード画像として挿入してください。'])
                    ->withInput();
            }

            // date convert
            $start_date = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $request['start_date'])));
            $end_date = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $request['end_date'])));

            if (!empty($request['coupon_date_start']) && !empty($request['coupon_date_end'])) {
                $coupon_date_start = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $request['coupon_date_start'])));
                $coupon_date_end = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $request['coupon_date_end'])));
            } else {
                $coupon_date_start = null;
                $coupon_date_end = null;
            }

            if (isset($request['images']) && $request['images']) {
                //画像チェック
                $fileSize = $request['images']->getSize();
                $maxSize = 1000 * 1024 * 1024; // 一旦1GB制限
                if ($fileSize > $maxSize) {
                    return redirect()->route('admin.special_future.create')
                        ->withErrors("ファイルサイズが1GBを超えています。")
                        ->withInput();
                }

                $img = $request['images'];
                if (strpos($request['images']->getMimeType(), 'image') !== false) {
                    $img_path = $img->store('special_future_image', 'pub_images');
                } else {
                    $img_path = '';
                }
            } else {
                $img_path = '';
            }

            //クーポン登録
            DB::beginTransaction();
            try {
                $create_special_future_array = array();
                $create_special_future_array['name'] = $request['name'];
                $create_special_future_array['outline'] = $request['outline'];
                $create_special_future_array['detail'] = $request['detail'];
                $create_special_future_array['start_date'] = $start_date;
                $create_special_future_array['end_date'] = $end_date;
                $create_special_future_array['image'] = $img_path;
                $create_special_future_array['coupon_date_start'] = $coupon_date_start;
                $create_special_future_array['coupon_date_end'] = $coupon_date_end;

                if ($request['sex'] != null) {
                    $create_special_future_array['sex'] = $request['sex'];
                } else {
                    $create_special_future_array['sex'] = 99; // all
                }

                if (isset($request['discount_rate']) && $request['discount_rate'] > 0) {
                    $create_special_future_array['discount_rate'] = $request['discount_rate'];
                }

                if (isset($request['genre']) && $request['genre'] !== '') {
                    $create_special_future_array['genre'] = $request['genre'];
                } else {
                    $create_special_future_array['genre'] = 99; // all
                }

                if (!empty($request['day_of_week'])) {
                    $create_special_future_array['day_of_the_weeks'] = json_encode($request['day_of_week']);
                } else {
                    $create_special_future_array['day_of_the_weeks'] = null;
                }

                $create_special_future_array['created_by'] = $user->id;
                $create_special_future_array['updated_by'] = $user->id;

                SpecialFutures::create($create_special_future_array);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                echo $e;
                exit;
            }

            return redirect('/admin/special_future');
        }

        return view('admin.special_future.create', compact('user', 'stores', 'defaultweeks'));
    }

    public function edit(Request $request)
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $stores = Stores::select()->where('company_id', $user->company_id)->get(); //stores情報
        $request = $request->all();

        if (!isset($request['id'])) {
            abort(404);
        }

        $special_future_id = $request['id'];
        $special_future_data = SpecialFutures::where('id', $special_future_id)->first();

        if (!$special_future_data) {
            abort(404);
        }

        $special_future_data->start_date = date('Y-m-d H:i', strtotime($special_future_data->start_date));
        $special_future_data->end_date = date('Y-m-d H:i', strtotime($special_future_data->end_date));

        $defaultweeks = !empty($special_future_data->day_of_the_weeks)
            ? json_decode($special_future_data->day_of_the_weeks, true)
            : [];

        if (isset($request['name']) && isset($request['p_type']) && $request['p_type'] == 'edit') {
            $validated_data = Validator::make($request, [
                'name' => 'required|max:190',
                'detail' => 'required',
                'start_date' => 'required',
                'end_date' => 'required'
            ]);

            if ($validated_data->fails()) {
                return redirect()->back()
                    ->withErrors($validated_data)
                    ->withInput();
            }

            $request['detail'] = $this->convertEmbeddedDetailImages($request['detail']);

            if ($this->hasEmbeddedDetailImages($request['detail'])) {
                return redirect()->back()
                    ->withErrors(['detail' => '本文内の画像はアップロード画像として挿入してください。'])
                    ->withInput();
            }

            // date convert
            $start_date = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $request['start_date'])));
            $end_date = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $request['end_date'])));

            if (!empty($request['coupon_date_start']) && !empty($request['coupon_date_end'])) {
                $coupon_date_start = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $request['coupon_date_start'])));
                $coupon_date_end = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $request['coupon_date_end'])));
            } else {
                $coupon_date_start = null;
                $coupon_date_end = null;
            }

            if (isset($request['images']) && $request['images']) {
                //画像チェック
                $fileSize = $request['images']->getSize();
                $maxSize = 1000 * 1024 * 1024; // 一旦1GB制限
                if ($fileSize > $maxSize) {
                    return redirect()->back()
                        ->withErrors("ファイルサイズが1GBを超えています。")
                        ->withInput();
                }

                $img = $request['images'];
                if (strpos($request['images']->getMimeType(), 'image') !== false) {
                    $img_path = $img->store('special_future_image', 'pub_images');
                } else {
                    $img_path = $special_future_data->image;
                }
            } else {
                $img_path = $special_future_data->image;
            }

            //編集
            DB::beginTransaction();
            try {
                $create_special_future_array = array();
                $create_special_future_array['name'] = $request['name'];
                $create_special_future_array['outline'] = $request['outline'];
                $create_special_future_array['detail'] = $request['detail'];
                $create_special_future_array['start_date'] = $start_date;
                $create_special_future_array['end_date'] = $end_date;
                $create_special_future_array['coupon_date_start'] = $coupon_date_start;
                $create_special_future_array['coupon_date_end'] = $coupon_date_end;
                $create_special_future_array['image'] = $img_path;

                if ($request['sex'] != null) {
                    $create_special_future_array['sex'] = $request['sex'];
                } else {
                    $create_special_future_array['sex'] = 99; // all
                }

                if (isset($request['discount_rate']) && $request['discount_rate'] > 0) {
                    $create_special_future_array['discount_rate'] = $request['discount_rate'];
                }

                if (isset($request['genre']) && $request['genre'] !== '') {
                    $create_special_future_array['genre'] = $request['genre'];
                } else {
                    $create_special_future_array['genre'] = 99; // all
                }

                if (!empty($request['day_of_week'])) {
                    $create_special_future_array['day_of_the_weeks'] = json_encode($request['day_of_week']);
                } else {
                    $create_special_future_array['day_of_the_weeks'] = null;
                }

                $create_special_future_array['updated_by'] = $user->id;

                SpecialFutures::where('id', $special_future_data->id)->update($create_special_future_array);
                $this->deleteRemovedDetailImages($special_future_data->detail, $request['detail']);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                echo $e;
                exit;
            }

            return redirect('/admin/special_future');
        }

        return view('admin.special_future.edit', compact('user', 'stores', 'special_future_data', 'defaultweeks'));
    }

    public function delete(Request $request)
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $request = $request->all();

        $special_future_id = $request['id'];
        $special_future_data = SpecialFutures::where('id', $special_future_id)->first(); //クーポン情報

        if ($special_future_data) {
            //一旦物理削除
            SpecialFutures::where('id', $special_future_id)->delete();

            if ($special_future_data->image && File::exists('assets/images/'. $special_future_data->image)) {
                File::delete('assets/images/'. $special_future_data->image);
            }
            $this->deleteDetailImages($special_future_data->detail);
        }

        return redirect('/admin/special_future');
    }

    public function uploadDetailImage(Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json(['message' => '画像を選択してください。'], 422);
        }

        $file = $request->file('image');

        if (strpos($file->getMimeType(), 'image') === false) {
            return response()->json(['message' => '画像ファイルを選択してください。'], 422);
        }

        $maxSize = 10 * 1024 * 1024;
        if ($file->getSize() > $maxSize) {
            return response()->json(['message' => '画像サイズは10MB以内にしてください。'], 422);
        }

        $path = $file->store('special_future_detail', 'pub_images');

        return response()->json([
            'path' => $path,
            'url' => asset('/assets/images/'. $path),
        ]);
    }

    private function hasEmbeddedDetailImages($detail)
    {
        return preg_match('/<img[^>]+src=["\']data:image\//i', $detail ?? '') === 1;
    }

    private function convertEmbeddedDetailImages($detail)
    {
        return preg_replace_callback(
            '~<img\b([^>]*?)\bsrc=(["\'])(data:image/(png|jpeg|jpg|gif|webp);base64,([^"\']+))\2([^>]*)>~i',
            function ($matches) {
                $extension = strtolower($matches[4]);
                $extension = $extension === 'jpeg' ? 'jpg' : $extension;
                $base64 = preg_replace('/\s+/', '', $matches[5]);
                $binary = base64_decode($base64, true);

                if ($binary === false || strlen($binary) > 10 * 1024 * 1024) {
                    return $matches[0];
                }

                $path = 'special_future_detail/'. date('YmdHis') .'_'. bin2hex(random_bytes(8)) .'.'. $extension;
                Storage::disk('pub_images')->put($path, $binary);

                return '<img'. $matches[1] .'src='. $matches[2] . asset('/assets/images/'. $path) . $matches[2] . $matches[6] .'>';
            },
            $detail ?? ''
        );
    }

    private function extractDetailImagePaths($detail)
    {
        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $detail ?? '', $matches);

        $paths = [];
        foreach ($matches[1] ?? [] as $src) {
            $path = parse_url($src, PHP_URL_PATH);
            if (!$path) {
                continue;
            }

            $prefix = '/assets/images/';
            $prefixPos = strpos($path, $prefix);
            if ($prefixPos === false) {
                continue;
            }

            $relativePath = ltrim(substr($path, $prefixPos + strlen($prefix)), '/');
            if (strpos($relativePath, 'special_future_detail/') !== 0 || strpos($relativePath, '..') !== false) {
                continue;
            }

            $paths[] = $relativePath;
        }

        return array_values(array_unique($paths));
    }

    private function deleteRemovedDetailImages($oldDetail, $newDetail)
    {
        $oldImages = $this->extractDetailImagePaths($oldDetail);
        $newImages = $this->extractDetailImagePaths($newDetail);

        foreach (array_diff($oldImages, $newImages) as $path) {
            if (Storage::disk('pub_images')->exists($path)) {
                Storage::disk('pub_images')->delete($path);
            }
        }
    }

    private function deleteDetailImages($detail)
    {
        foreach ($this->extractDetailImagePaths($detail) as $path) {
            if (Storage::disk('pub_images')->exists($path)) {
                Storage::disk('pub_images')->delete($path);
            }
        }
    }
}

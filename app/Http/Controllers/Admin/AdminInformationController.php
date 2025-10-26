<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Stores;
use App\Models\Information;

class AdminInformationController extends Controller
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
        $information_array = Information::select()->where('delete_flg', 0)->orderBy('created_at', 'DESC')->get(); //特集情報
        $stores = Stores::select()->get(); //stores情報
        return view('admin.information.index', compact('user', 'stores', 'information_array'));
    }

    public function create(Request $request)
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $stores = Stores::select()->get(); //stores情報
        $request = $request->all();

        if (isset($request['name'])) {
            $validated_data = Validator::make($request, [
                'name' => 'required|max:190',
                'detail' => 'required',
                'start_date' => 'required',
                'end_date' => 'required'
            ]);

            if ($validated_data->fails()) {
                return redirect()->route('admin.information.create')
                    ->withErrors($validated_data)
                    ->withInput();
            }

            //date covert
            $start_date = str_replace('T', ' ', $request['start_date']).':00';
            $end_date = str_replace('T', ' ', $request['end_date']).':59';

            //おしらせ登録
            DB::beginTransaction();
            try {
                $create_information_array = array();
                $create_information_array['name'] = $request['name'];
                $create_information_array['detail'] = $request['detail'];
                $create_information_array['start_date'] = $start_date;
                $create_information_array['end_date'] = $end_date;
                $create_information_array['created_by'] = $user->id;
                $create_information_array['updated_by'] = $user->id;

                Information::firstOrCreate($create_information_array);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                echo $e;
                exit;
            }

            return redirect('/admin/information');
        }

        return view('admin.information.create', compact('user', 'stores'));
    }

    public function edit(Request $request)
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $stores = Stores::select()->where('company_id', $user->company_id)->get(); //stores情報
        $request = $request->all();

        if(!isset($request['id'])) {
            abort(404);
        }

        $information_id = $request['id'];
        $information_data = Information::where('id', $information_id)->first();

        if(!$information_data) {
            abort(404);
        }

        $information_data->start_date = date('Y-m-d H:i', strtotime($information_data->start_date));
        $information_data->end_date = date('Y-m-d H:i', strtotime($information_data->end_date));

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

            //date covert
            $start_date = str_replace('T', ' ', $request['start_date']).':00';
            $end_date = str_replace('T', ' ', $request['end_date']).':59';

            //編集
            DB::beginTransaction();
            try {
                $create_information_array = array();
                $create_information_array['name'] = $request['name'];
                $create_information_array['detail'] = $request['detail'];
                $create_information_array['start_date'] = $start_date;
                $create_information_array['end_date'] = $end_date;
                $create_information_array['updated_by'] = $user->id;

                Information::where('id', $information_data->id)->update($create_information_array);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                echo $e;
                exit;
            }

            return redirect('/admin/information');
        }

        return view('admin.information.edit', compact('user', 'stores', 'information_data'));
    }

    public function delete(Request $request)
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $request = $request->all();

        $information_id = $request['id'];
        $information_data = Information::where('id', $information_id)->first(); //クーポン情報

        if ($information_data) {
            //一旦物理削除
            Information::where('id', $information_id)->delete();

            if ($information_data->image && File::exists('assets/images/'. $information_data->image)) {
                File::delete('assets/images/'. $information_data->image);
            }
        }

        return redirect('/admin/information');
    }
}

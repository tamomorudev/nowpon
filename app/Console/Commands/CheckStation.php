<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use App\Models\Coupons;
use App\Models\Stores;
use App\Models\Stations;

class CheckStation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:station';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'stations update';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    //一旦HeartRails Express API使用
    public function handle()
    {
        //transaction
        DB::beginTransaction();

        try {

            //駅テーブルリセット
            DB::table('stations')->delete();
            //DB::statement('ALTER TABLE stations AUTO_INCREMENT = 1'); //transaction使う場合使用不可

            //1.都道府県情報
            $curl = curl_init();
            curl_setopt_array($curl, [
                //CURLOPT_URL => "https://express.heartrails.com/api/json?method=getAreas", //エリア情報
                CURLOPT_URL => "https://express.heartrails.com/api/json?method=getPrefectures", //都道府県情報
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json",
                    "Accept: application/json"
                ],
            ]);
                
            $response = curl_exec($curl);
            curl_close($curl);

            $prefectures = json_decode($response);

            //2. 路線情報
            foreach ($prefectures->response->prefecture as $prefecture) {
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://express.heartrails.com/api/json?method=getLines&prefecture=".urlencode($prefecture), //路線API
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_HTTPHEADER => [
                        "Content-Type: application/json",
                        "Accept: application/json"
                    ],
                ]);

                $response = curl_exec($curl);
                curl_close($curl);

                $lines = json_decode($response);

                //3. 駅情報
                foreach ($lines->response->line as $line) {
                    $insert_stations = array();
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => "https://express.heartrails.com/api/json?method=getStations&line=".urlencode($line), //駅API
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_HTTPHEADER => [
                            "Content-Type: application/json",
                            "Accept: application/json"
                        ],
                    ]);

                    $response = curl_exec($curl);
                    curl_close($curl);

                    $stations = json_decode($response);

                    //駅マスター更新
                    $insert_key = 0;
                    foreach ($stations->response->station as $station_obj) {
                        foreach ($station_obj as $obj_key => $data) {
                            $insert_stations[$insert_key][$obj_key] = $data;
                        }
                        $insert_stations[$insert_key]['created_at'] = date('Y-m-d H:i:s');
                        $insert_stations[$insert_key]['updated_at'] = date('Y-m-d H:i:s');

                        $insert_key++;
                    }

                    //insert
                    DB::table('stations')->insert($insert_stations);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
        }

        return true;
    }
}

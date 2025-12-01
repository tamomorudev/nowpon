<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Zipcodes;

class import_zipcodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:zipcodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import zipcodes';

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
    public function handle()
    {
        // 固定パスを指定
        $filePath = storage_path('app/data/utf_ken_all.csv');

        if (!file_exists($filePath)) {
            $this->error("CSVファイルが見つかりません: {$filePath}");
            return 1;
        }

        if (($handle = fopen($filePath, "r")) !== false) {
            $rowCount = 0;

            while (($row = fgetcsv($handle)) !== false) {
                // CSVのカラム調整
                $zipcode    = trim($row[2]); // 郵便番号（7桁）
                $prefecture = $row[6]; // 都道府県
                $city       = $row[7]; // 市区町村
                $town       = preg_replace('/（.*?）/', '', $row[8]); // 町域

                Zipcodes::updateOrCreate(
                    ['zipcode' => $zipcode],
                    [
                        'prefecture' => $prefecture,
                        'city'       => $city,
                        'town'       => $town,
                    ]
                );

                $rowCount++;
            }

            fclose($handle);
            $this->info("{$rowCount} 件の郵便番号をインポートしました。");
        }

        return 0;
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class EntireInstanceCountService
{
    /**
     * 自增设备实例数量
     * @param string $entireModelUniqueCode
     * @return int
     * @throws \Throwable
     */
    public function inc(string $entireModelUniqueCode): int
    {
        $entireFixedCountDB = DB::table('entire_fixed_counts')->where('entire_model_unique_code', $entireModelUniqueCode, date('Y'))->first(['count']);
        if ($entireFixedCountDB) {
            $entireFixedCount = $entireFixedCountDB ? $entireFixedCountDB->count : 0;
            DB::table('entire_fixed_counts')->where('entire_model_unique_code', $entireModelUniqueCode, date('Y'))->update(['count' => $entireFixedCount + 1]);
            return $entireFixedCount + 1;
        } else {
            DB::table('entire_fixed_counts')->insert(['entire_model_unique_code' => $entireModelUniqueCode, 'year' => date('Y'), 'count' => 1]);
            return 1;
        }
    }
}

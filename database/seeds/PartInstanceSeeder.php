<?php

use Illuminate\Database\Seeder;

class PartInstanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date('Y-m-d');
        $i = 0;
        $partInstances = [];
        foreach (\App\Model\EntireInstance::where('category_unique_code', 'S03')->get() as $entireInstance) {
            $i++;
            $partInstance = [
                'created_at' => $time,
                'updated_at' => $time,
                'part_model_unique_code' => $entireInstance->entire_model_id_code,
                'entire_instance_identity_code' => $entireInstance->identity_code,
                'status' => 'FIXED',
                'factory_name' => '西安铁路信号设备有限责任公司',
                'factory_device_code' => time() . $i,
                'identity_code' => time() . $i,
                'entire_instance_serial_number' => $entireInstance->identity_code,
                'cycle_fix_count' => 0,
                'un_cycle_fix_count' => 0,
            ];
            \Illuminate\Support\Facades\DB::table('part_instances')->insert($partInstance);
        }
//        Storage::disk('local')->put('test/partInstances.json', json_encode($partInstances, 256));
    }
}

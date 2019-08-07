<?php

use Illuminate\Database\Seeder;

class InstalledSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\Model\EntireInstance::with(['FixWorkflow'])->where('status', 'FIXED')->get() as $entireInstance) {
            if (rand(0, 2) == 1) {
                $entireInstance->fill(['status' => 'INSTALLED', 'in_warehouse' => false, 'maintain_station_name' => '十里冲'])->saveOrFail();
                $entireInstance->FixWorkflow->fill(['status' => 'FIXED'])->saveOrFail();
            }
        }
    }
}

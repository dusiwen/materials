<?php

use App\Model\FixWorkflow;
use Illuminate\Database\Seeder;

class OnlyOnceFixedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (FixWorkflow::with(['EntireInstance'])->where('status', 'FIXED')->get() as $fixWorkflow) {
            if (rand(0, 1)) {
                $fixWorkflow->fill(['status' => 'FIXED', 'entire_fix_after_count' => 1, 'part_fix_after_count' => 1, 'is_cycle' => true])->saveOrFail();
                $fixWorkflow->EntireInstance->fill(['status' => 'FIXED'])->saveOrFail();
            }
        }
    }
}

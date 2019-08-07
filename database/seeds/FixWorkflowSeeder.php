<?php

use App\Facades\Code;
use App\Model\EntireInstance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixWorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i = 0;
        foreach (EntireInstance::whereNotIn('status', ['INSTALLING', 'INSTALLED'])->get() as $entireInstance) {
            $fixWorkflowSerialNumber = Code::makeSerialNumber('FIX_WORKFLOW') . strval(++$i);
            $fixWorkflow = [
                'created_at' => $entireInstance->created_at,
                'updated_at' => $entireInstance->created_at,
                'entire_instance_identity_code' => $entireInstance->identity_code,
                'warehouse_report_serial_number' => null,
                'status' => 'FIXING',
                'processor_id' => rand(1, 21),
                'expired_at' => null,
                'id_by_failed' => null,
                'serial_number' => $fixWorkflowSerialNumber,
                'note' => null,
                'processed_times' => 0,
                'stage' => 'PART',
                'is_cycle' => false,
                'entire_fix_after_count' => 0,
                'part_fix_after_count' => 0,
            ];
            DB::table('fix_workflows')->insert($fixWorkflow);
            $entireInstance->fill(['fix_workflow_serial_number' => $fixWorkflowSerialNumber, 'status' => 'FIXING', 'in_warehouse' => false])->saveOrFail();
        }

        foreach (EntireInstance::whereIn('status', ['INSTALLING', 'INSTALLED'])->get() as $item) {
            $fixWorkflowSerialNumber = Code::makeSerialNumber('FIX_WORKFLOW') . strval(++$i);
            $fixWorkflow = [
                'created_at' => $entireInstance->created_at,
                'updated_at' => $entireInstance->created_at,
                'entire_instance_identity_code' => $entireInstance->identity_code,
                'warehouse_report_serial_number' => null,
                'status' => 'FIXED',
                'processor_id' => 1,
                'expired_at' => null,
                'id_by_failed' => null,
                'serial_number' => $fixWorkflowSerialNumber,
                'note' => null,
                'processed_times' => 0,
                'stage' => 'FIXED',
                'is_cycle' => false,
                'entire_fix_after_count' => 0,
                'part_fix_after_count' => 0,
            ];

            DB::table('fix_workflows')->insert($fixWorkflow);
            $entireInstance->fill(['fix_workflow_serial_number' => $fixWorkflowSerialNumber, 'status' => 'FIXED', 'in_warehouse' => false])->saveOrFail();
        }
    }
}

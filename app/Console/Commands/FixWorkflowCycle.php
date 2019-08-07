<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixWorkflowCycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:FixWorkflowCycle {year?} {month?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动总结检修单';

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
     * @return mixed
     */
    public function handle()
    {
        $year = $this->argument('year') ?: date('Y');
        $month = $this->argument('month') ?: date('m');

        list($currentYear, $currentMonth) = explode('-', date('Y-m'));
        \App\Facades\FixWorkflowCycle::getBasicInfo($currentYear, $currentMonth);
        \App\Facades\FixWorkflowCycle::getLastMonthFixedCount($currentYear, $currentMonth);
        \App\Facades\FixWorkflowCycle::getCurrentMonthGoingToFixCount($currentYear, $currentMonth);
        $entireInstances = \App\Facades\FixWorkflowCycle::getEntireInstanceIdentityCodesForGoingToAutoMakeFixWorkflow($currentYear, $currentMonth);
        \App\Facades\FixWorkflowCycle::autoMakeFixWorkflow($entireInstances);

        $this->line("已执行：{$year}年{$month}月");
    }
}

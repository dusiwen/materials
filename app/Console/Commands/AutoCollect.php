<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AutoCollect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:AutoCollect {type} {factoryDeviceCode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动采集';

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
        try {
            # 获取command参数
            $type = $this->argument('type');
            $factoryDeviceCode = $this->argument('factoryDeviceCode');

            # 自动生成测试值
            $testData = \App\Facades\AutoCollect::makeTestData($type, $factoryDeviceCode);
            # 输出测试结果
            $this->table(['生成时间', '修改时间', '测试单号', '整件绑定', '部件绑定', '说明', '标准值识别码', '实测值', '测试人', '测试时间', '测试记录序列号', '类型', '是否通过'], $testData);

            #  保存测试值
            \App\Facades\AutoCollect::saveTestData($testData);
        } catch (ModelNotFoundException $exception) {
            $this->line($exception->getMessage());
        } catch (\Exception $exception) {
            $this->line($exception->getMessage());
        }
    }
}

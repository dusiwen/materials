<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(EntireInstanceSeeder::class);  # 生成整件
        $this->call(PartInstanceSeeder::class);  # 生成部件
        $this->call(FixWorkflowSeeder::class);  # 生成检修单
        $this->call(InstalledSeeder::class);  # 标记已安装
        $this->call(OnlyOnceFixedSeeder::class);
        $this->call(EntireInstanceInstalledSeeder::class);  # 已安装设备
    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AlarmUseWechat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $timeout = 120;

    private $_title = null;
    private $_content = null;
    private $_to = null;

    /**
     * Create a new job instance.
     *
     * AlarmUseWechat constructor.
     * @param $title
     * @param $content
     * @param $to
     */
    public function __construct($title, $content, $to)
    {
        $this->_title = $title;
        $this->_content = $content;
        $this->_to = $to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

    }
}

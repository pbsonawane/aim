<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenrateReport extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $report_id;
    protected $export_type;
    protected $notification_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($report_id,$export_type,$notification_id)
    {
        $this->report_id       = $report_id;
        $this->export_type     = $export_type;
        $this->notification_id = $notification_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call("report:generate", ['report_id' => $this->report_id,'type'=>$this->export_type,'notification_id'=>$this->notification_id]);
    }
}
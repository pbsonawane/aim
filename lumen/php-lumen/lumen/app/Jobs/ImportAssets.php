<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\ImportAssetsService;

class ImportAssets extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $notification_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notification_id)
    {
        $this->notification_id = $notification_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ImportAssetsService $importassets)
    {
        $resp_data = $importassets->importasset($this->notification_id);        
    }
}
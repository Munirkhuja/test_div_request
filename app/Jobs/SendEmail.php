<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $emailMessage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emailMessage)
    {
        $this->emailMessage = json_encode($emailMessage);
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fileName = 'email_' . now()->format('Ymd_His') . '.txt';
        Storage::put('emails/' . $fileName, $this->emailMessage);
    }
}

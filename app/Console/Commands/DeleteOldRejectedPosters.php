<?php

namespace App\Console\Commands;

use App\Models\RejectedEvents;
use Illuminate\Console\Command;

class DeleteOldRejectedPosters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posters:delete-old-rejected-posters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command deletes rejected posters from the database that were rejected more than 2 months ago.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        RejectedEvents::where('created_at', '<', now()->subMonths(2))->delete();
    }
}

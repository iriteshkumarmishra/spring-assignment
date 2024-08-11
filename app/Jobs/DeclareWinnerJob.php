<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\{User, Winner};

class DeclareWinnerJob implements ShouldQueue
{
    use Queueable;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 20;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 20;
    
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
     public $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Find the users with the highest points
        $maxPoints = User::max('points');
        $users = User::where('points', $maxPoints)->limit(2); // to fetch only 2 records to find if there is any tie

        // If there's exactly one user with the max points, declare them as the winner
        if ($users->count() === 1) {
            $winner = $users->first();
            // Create a new record in the winners table
            Winner::create([
                'user_id' => $winner->id,
                'points' => $winner->points,
                'created_at' => now(),
            ]);
        }
    }
}

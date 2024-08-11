<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetUserPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-points';

    public $perChunk = 100;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all users points to zero';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to reset points for all users...');

        User::chunkById($this->perChunk, function ($users) {
            foreach ($users as $user) {
                $user->update(['points' => 0]);
            }
        });

        $this->info('All users points have been reset to zero.');
    }
}

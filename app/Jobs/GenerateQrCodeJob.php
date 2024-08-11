<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class GenerateQrCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $client;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 50;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 100;
    
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
     public $tries = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->client = new Client();
        $address = $this->user->address->fullAddress();

        try {
            // Request the QR code from the API
            $response = $this->client->get(config('params.qr_code_settings.endpoint'), [
                'query' => [
                    'data' => $address,
                    'size' => config('params.qr_code_settings.size'),
                ]
            ]);
    
            if ($response->getStatusCode() === 200) {
                // Save the QR code image locally
                $qrCodeContent = $response->getBody()->getContents();
                $fileName = 'qrcodes/user_' . $this->user->id . '.png';
    
                Storage::disk('public')->put($fileName, $qrCodeContent);
    
                // Optionally, save the file path to the user record
                $this->user->qr_code_path = $fileName;
                $this->user->save();
            }
            else {
                // Something went wrong with the create-qr-code API
                throw new \Exception("Something went wrong, create-qr-code API failed!");

            }
        }
        catch(RequestException $e) {
            // Log the exception for debugging
            Log::error('QR Code generation failed for User ID: ' . $this->user->id, [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // throw the exception to keep the job in failed_jobs table to debug and retry later.
            throw $e;
        }
        
    }
}
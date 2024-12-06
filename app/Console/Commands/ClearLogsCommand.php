<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the Laravel log file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $logFilePath = storage_path('logs/laravel.log');

        if (File::exists($logFilePath)) {
            File::put($logFilePath, '');
            $this->info('Log fayli muvaffaqiyatli tozalandi!');
        } else {
            $this->error('Log fayli topilmadi.');
        }

        return 0;
    }
}

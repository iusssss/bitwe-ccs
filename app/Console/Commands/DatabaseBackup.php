<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Backup;
use App\Helper\AppHelper as logger;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup Database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = '/home/pdj3kx98g8e9/public_html/backup/';
        Backup::setPath($path);
        Backup::export('backup-' . date('Ymd-His'));
        logger::createLog('Backup', 'Daily backup created', 1);
        return 'success';
    }
}

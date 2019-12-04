<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use Backup;
use App\Helper\AppHelper as logger;
class BackupsController extends Controller
{
    public function backup() {
    	Backup::export('backup-' . date('Ymd-His'));
        logger::createLog('Backup', 'Created database backup', auth()->user()->id);
    	return 'success';
    }
    public function restore(Request $request) {
    	$path = $request->input('path');
    	// $p = storage_path('app/public/backup/backup-20191123-171635.sql.gz');
    	Backup::restore($path);
        logger::createLog('Backup', 'Restored database', auth()->user()->id);
    	return 'success';
    }
    public function restorePaths() {
        $paths = Backup::getRestorationFiles();
        rsort($paths);
        array_splice($paths, 5);
        return $paths;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Backup;

class BackupsController extends Controller
{
    public function backup() {
    	Backup::export('backup-' . date('Ymd-His'));
    	return 'success';
    }
    public function restore(Request $request) {
    	$path = $request->input('path');
    	// $p = storage_path('app/public/backup/backup-20191123-171635.sql.gz');
    	Backup::restore($path);
    	return 'success';
    }
    public function restorePaths() {
    	return array_reverse(Backup::getRestorationFiles());
    }
}

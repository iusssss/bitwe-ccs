<?php
namespace App\Helper;

use App\Log;

class AppHelper
{
	public static function createLog($table, $description, $user_id) {
		$log = new Log();
		$log->table = $table;
		$log->description = $description;
		$log->user_id = $user_id;
		$log->save();
	}
}
<?php

use Illuminate\Database\Seeder;
use App\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
        	[
        		'id' => 1, 
	        	'module' => 'VoIP', 
	        	'state' => true,
        	],
        	[
        		'id' => 2, 
	        	'module' => 'Call Recording', 
	        	'state' => true,
        	],
        ];
		foreach ($items as $item) {
		    SystemSetting::updateOrCreate(['id' => $item['id']], $item);
		}
    }
}

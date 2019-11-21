<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
	protected $hidden = ['created_at', 'updated_at'];
	protected $fillable = ['state'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = ['criteria_id', 'question'];
}

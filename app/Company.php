<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // Table Name
    protected $table = 'companies';
    // Primary Key
    protected $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $hidden = ['created_at', 'updated_at'];

    public function clients(){
        return $this->hasMany('App\Client');
    }
    public function companyType(){
        return $this->belongsTo('App\CompanyType');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    // Table Name
    protected $table = 'clients';
    // Primary Key
    protected $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $hidden = ['created_at', 'updated_at'];

    public function company(){
        return $this->belongsTo('App\Company', 'company_id');
    }

    public function transactions(){
        return $this->hasMany('App\Transaction');
    }
}

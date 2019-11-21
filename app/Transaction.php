<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Table Name
    protected $table = 'transactions';
    // Primary Key
    protected $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function client(){
        return $this->belongsTo('App\Client');
    }

    public function service(){
        return $this->belongsTo('App\Service');
    }

    public function transactionSubject(){
        return $this->belongsTo('App\TransactionSubject');
    }
}

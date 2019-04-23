<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';
    protected $fillable = [
        'id',
        'type',
        'user_id',
        'amount'
    ];
    protected $guarded = ['created_at','updated_at'];
}

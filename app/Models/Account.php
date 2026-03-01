<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'bank',
        'account_number',
        'balance',
        'currency',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

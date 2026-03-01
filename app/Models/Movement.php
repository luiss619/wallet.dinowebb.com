<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'account_id',
        'service_id',
        'category_id',
        'subcategory_id',
        'quantity',
        'date',
        'description',
        'status',
    ];

    protected $casts = [
        'date'     => 'date',
        'quantity' => 'decimal:2',
    ];

    public function user()        { return $this->belongsTo(User::class); }
    public function account()     { return $this->belongsTo(Account::class); }
    public function service()     { return $this->belongsTo(Service::class); }
    public function category()    { return $this->belongsTo(Category::class); }
    public function subcategory() { return $this->belongsTo(Subcategory::class); }
}

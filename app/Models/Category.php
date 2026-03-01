<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'status'];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}

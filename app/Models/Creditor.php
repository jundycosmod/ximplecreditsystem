<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Creditor extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'is_active',
    
    ];
    
    
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/creditors/'.$this->getKey());
    }
}

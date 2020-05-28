<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddressType extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];
    public $timestamps = false;

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/address-types/'.$this->getKey());
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorGroup extends Model
{
    protected $fillable = ['company_id', 'name'];

    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'vendor_group_id');
    }
}
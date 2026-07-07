<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    // use HasFactory;

    protected $fillable = ['company_id', 'name', 'tel', 'balance', 'vendor_group_id'];

    public function transactions()
    {
        return $this->hasMany(VendorTransaction::class);
    }

    public function tags()
    {
        return $this->belongsToMany(VendorTag::class, 'vendor_vendor_tag', 'vendor_id', 'vendor_tag_id');
    }

    public function group()
    {
        return $this->belongsTo(VendorGroup::class, 'vendor_group_id');
    }

    public function company()
    {
        return $this->belongsTo(\App\company::class, 'company_id');
    }
}
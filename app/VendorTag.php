<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorTag extends Model
{
    // use HasFactory;

    protected $fillable = ['company_id', 'name'];

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_vendor_tag', 'vendor_tag_id', 'vendor_id');
    }
}
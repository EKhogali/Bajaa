<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    // use HasFactory;

    protected $fillable = ['company_id', 'name', 'tel', 'balance'];

    // Relationship with Transactions
    public function transactions()
    {
        return $this->hasMany(VendorTransaction::class);
    }

    // Relationship with Vendor Tags (Many-to-Many)
    public function tags()
    {
        return $this->belongsToMany(VendorTag::class, 'vendor_vendor_tag', 'vendor_id', 'vendor_tag_id');
    }
}
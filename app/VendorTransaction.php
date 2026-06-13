<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorTransaction extends Model
{
    // use HasFactory;

    protected $fillable = ['company_id', 'vendor_id', 'date', 'credit', 'debit', 'description', 'note'];

    // Relationship back to Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Relationship with Transaction Tags (Many-to-Many)
    public function tags()
    {
        return $this->belongsToMany(TransactionTag::class, 'transaction_transaction_tag', 'transaction_id', 'transaction_tag_id');
    }
}
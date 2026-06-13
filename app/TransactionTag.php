<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionTag extends Model
{
    // use HasFactory;

    protected $fillable = ['company_id', 'name'];

    public function transactions()
    {
        return $this->belongsToMany(VendorTransaction::class, 'transaction_transaction_tag', 'transaction_tag_id', 'transaction_id');
    }
}
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

    // Transactions whose vendor is visible to the given user — i.e. the
    // vendor is unrestricted, or the user is on that vendor's allow-list.
    // See Vendor::scopeVisibleToUser() / config/vendor_restrictions.php.
    public function scopeVisibleToUser($query, $userId)
    {
        return $query->whereHas('vendor', function ($v) use ($userId) {
            $v->visibleToUser($userId);
        });
    }
}
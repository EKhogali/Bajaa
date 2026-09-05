<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    // use HasFactory;

    protected $fillable = ['company_id', 'name', 'tel', 'balance', 'vendor_group_id', 'is_global'];


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

    public function companies()
{
    return $this->belongsToMany(\App\company::class, 'company_vendor', 'vendor_id', 'company_id');
}

// Vendors visible to a single company: global vendors + vendors explicitly linked to it
public function scopeVisibleTo($query, $companyId)
{
    return $query->where(function ($q) use ($companyId) {
        $q->where('is_global', true)
          ->orWhereHas('companies', function ($q2) use ($companyId) {
              $q2->where('companies.id', $companyId);
          });
    });
}

// Same, but for a set of companies (used by the "specific company" report filter)
public function scopeVisibleToAny($query, array $companyIds)
{
    return $query->where(function ($q) use ($companyIds) {
        $q->where('is_global', true)
          ->orWhereHas('companies', function ($q2) use ($companyIds) {
              $q2->whereIn('companies.id', $companyIds);
          });
    });
}

// vendor_id => [allowed user ids], from config/vendor_restrictions.php.
// A vendor absent from this map is visible to everyone.
protected static function restrictionMap(): array
{
    return config('vendor_restrictions', []);
}

// Vendors visible to a given user: unrestricted vendors, plus restricted
// vendors where this user is on the allow-list. Chain alongside
// visibleTo()/visibleToAny() wherever vendors are listed or reported on.
public function scopeVisibleToUser($query, $userId)
{
    $blockedIds = collect(static::restrictionMap())
        ->filter(fn ($allowedUserIds) => !in_array($userId, $allowedUserIds, true))
        ->keys();

    if ($blockedIds->isNotEmpty()) {
        $query->whereNotIn('id', $blockedIds);
    }

    return $query;
}

// Whether this specific vendor instance is visible to the given user.
// Use this to guard direct access (edit/update/delete/receipt) by ID,
// since a route-model lookup bypasses the visibleToUser() query scope.
public function isVisibleToUser($userId): bool
{
    $allowed = static::restrictionMap()[$this->id] ?? null;

    return $allowed === null || in_array($userId, $allowed, true);
}
}
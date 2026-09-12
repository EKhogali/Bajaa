<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    // use HasFactory;

    protected $fillable = ['company_id', 'name', 'tel', 'balance', 'vendor_group_id', 'is_global'];

    // Only these user IDs can restrict/unrestrict a vendor's visibility from
    // the vendor form. Hardcoded on purpose — this list itself isn't meant to
    // be editable through the UI.
    public const RESTRICTION_MANAGERS = [1, 2];

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

    // The users a restricted vendor is allowed to be seen by. A vendor with
    // no rows here is unrestricted (visible to everyone). Managed from the
    // vendor create/edit form by users in RESTRICTION_MANAGERS.
    public function restrictedUsers()
    {
        return $this->belongsToMany(\App\User::class, 'vendor_restricted_users', 'vendor_id', 'user_id');
    }

    // Vendors visible to a given user: unrestricted vendors, plus restricted
    // vendors where this user is on the allow-list. Chain alongside
    // visibleTo()/visibleToAny() wherever vendors are listed or reported on.
    public function scopeVisibleToUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->whereDoesntHave('restrictedUsers')
              ->orWhereHas('restrictedUsers', function ($r) use ($userId) {
                  $r->where('user_id', $userId);
              });
        });
    }

    // Whether this specific vendor instance is visible to the given user.
    // Use this to guard direct access (edit/update/delete/receipt) by ID,
    // since a route-model lookup bypasses the visibleToUser() query scope.
    public function isVisibleToUser($userId): bool
    {
        if (!$this->relationLoaded('restrictedUsers')) {
            $this->load('restrictedUsers');
        }

        return $this->restrictedUsers->isEmpty() || $this->restrictedUsers->contains('id', $userId);
    }
}
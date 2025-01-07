<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll_item_type extends Model
{
    protected $fillable = [
        'name',
        'type',
        'archived',
        'created_by',
        'updated_by',
    ];
    public function transaction()
    {
        return $this->hasMany(Payroll_transaction::class);
    }
}

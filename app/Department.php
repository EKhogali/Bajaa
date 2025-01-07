<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'company_id',
        'archived',
        'created_by',
        'updated_by',
    ];
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}

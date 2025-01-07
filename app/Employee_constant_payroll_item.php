<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee_constant_payroll_item extends Model
{
    protected $fillable = [
        'descrpt',
        'name',
        'amount',
        'employee_id',
        'payroll_item_type_id',
        'archived'
    ];
    public function payrollitem()
    {
        return $this->belongsTo(Payroll_item_type::class, 'payroll_item_type_id');
    }
    public function payrollItemType()
    {
        return $this->belongsTo(Payroll_item_type::class);
    }
}

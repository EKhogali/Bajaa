<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll_transaction extends Model
{
    protected $fillable = [
        'notes','year', 'month', 'amount', 'company_id', 'employee_id', 'payroll_item_type_id', 'archived', 'created_by', 'updated_by'
    ];

    public static function where(string $string, $id)
    {
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollItemType()
    {
        return $this->belongsTo(Payroll_item_type::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class,'created_by');
    }

}

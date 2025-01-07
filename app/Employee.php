<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'code',
        'job_id',
        'department_id',
        'basic_salary',
        'hire_date',
        'gender',
        'dob',
        'marital_state_id',
        'archived',
        'company_id',
        'created_by',
        'updated_by',
    ];
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
    public function constantPayrollItems()
    {
        return $this->hasMany(Employee_constant_payroll_item::class);
    }
    public function payrollTransactions()
    {
        return $this->hasMany(Payroll_transaction::class);
    }


}

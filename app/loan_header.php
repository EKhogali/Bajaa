<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan_header extends Model
{
    protected $fillable = [
        'descrpt', 'amount', 'months', 'start_year', 'start_month', 'employee_id',
        'company_id', 'archived', 'created_by', 'updated_by'
    ];

    public function loanDetails()
    {
        return $this->hasMany(loan_detail::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

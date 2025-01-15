<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan_detail extends Model
{
    protected $fillable = [
        'loan_header_id', 'year', 'month', 'amount', 'done',
        'company_id', 'archived', 'created_by', 'updated_by'
    ];

    public function loanHeader()
    {
        return $this->belongsTo(loan_header::class);
    }
}

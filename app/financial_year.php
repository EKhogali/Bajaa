<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class financial_year extends Model
{
    public function company(){
        return $this->belongsTo(company::class);
    }
}

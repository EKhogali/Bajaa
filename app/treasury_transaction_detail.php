<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class treasury_transaction_detail extends Model
{
    public function account(){
        return $this->belongsTo(account::class);
    }
}

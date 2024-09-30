<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class partner extends Model
{
    public function account(){
        return $this->belongsTo(account::class);
    }

    public function treasuryTransactions() {
        return $this->hasMany(treasury_transaction::class, 'account_id', 'account_id')
            ->where('company_id', session::get('company_id'))
            ->where('financial_year', session::get('financial_year'))
            ->where('archived', 0)
            ->where('tag_id', 1);
    }

}

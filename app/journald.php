<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class journald extends Model
{
    public function accountR(){
        return $this->belongsTo(account::class,'account_id','id');
    }

    public function journalmR(){
        return $this->belongsTo(journalm::class,'journalm_id','id');
    }

//    public function accountR(){
//        return $this->belongsTo(account::class);
//    }
}

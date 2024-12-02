<?php

namespace App;

use App\Http\Controllers\CategoryController;
use Illuminate\Database\Eloquent\Model;

class account extends Model
{
    public function category(){
        return $this->belongsto(category::class);
    }
    public function classification(){
        return $this->belongsto(Classification::class);
    }
    public function parentR(){
        return $this->belongsto(account::class,'parent_id','id');
    }
    public function accR(){
        return $this->hasMany(journald::class,'account_id','id');
    }
//    public function account(){
//        return $this->hasMany(treasury_transaction::class,'account_id','id');
//    }
}

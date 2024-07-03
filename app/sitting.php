<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sitting extends Model
{
    public function CashboxFaaedAccount(){
        return $this->belongsTo(account::class,'Cashbox_Faaed_Account','id');
    }
    public function CashboxAjzAccount(){
        return $this->belongsTo(account::class,'Cashbox_Ajz_Account');
    }
    public function OperationAccount(){
        return $this->belongsTo(category::class,'operation_accounts_category');
    }
    public function AdministrativeAccount(){
        return $this->belongsTo(category::class,'administrative_accounts_category');
    }
    public function OtherIncom(){
        return $this->belongsTo(category::class,'Other_Incom');
    }
    public function DioonAccountCategory(){
        return $this->belongsTo(category::class,'dioon_account_category');
    }
    public function PulledFromNetIncomeAccountsCategory(){
        return $this->belongsTo(category::class,'pulled_from_net_income_accounts_category');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user_permission extends Model
{
    protected $fillable=['user_id','permission_name'];
}

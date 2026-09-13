<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    const HIDDEN_FROM_OTHERS_ID = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company(){
        return $this->belongsTo(company::class,'company_id','id');
    }

    public function permissions()
{
    return $this->hasMany(\App\user_permission::class, 'user_id');
}

// Whether this user can access a given module/page. Admins and supervisors
// always can; everyone else needs an explicit grant in user_permissions.
// See config/access_modules.php for the list of keys, and the
// /access-permissions screen that grants/revokes them.
public function hasModuleAccess(string $key): bool
{
    if (in_array($this->type, [1, 2], true)) {
        return true;
    }

    return $this->permissions()->where('permission_name', $key)->exists();
}
}

<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname', 'email', 'password',
      ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function policies()
    {
    return $this->belongsToMany('App\Policy', 'user__policies');
    }

    public function groups()
    {
        return $this->belongsTo('App\Group');
    }

    public function getJWTCustomClaims(): array{
      return [];
    }

    public function getJWTIdentifier()
    {
      return $this->getKey();
    }

    }

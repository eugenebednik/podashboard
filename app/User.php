<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_admin', 'active', 'api_token', 'alliance_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Is this user an admin?
     *
     * @return bool
     */
    public function isAdmin() : bool
    {
        return $this->is_admin ? true : false;
    }

    /**
     * Number of requests this user has served.
     *
     * @return int
     */
    public function requestCount() : int
    {
        return $this->requests->count();
    }

    /**
     * Is this user active?
     *
     * @return bool
     */
    public function isActive() : bool
    {
        return $this->active ? true : false;
    }

    /**
     * Relationship between the user and their Alliance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function alliance() : BelongsTo
    {
        return $this->belongsTo(Alliance::class);
    }

    /**
     * Requests this user has handled.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests() : HasMany
    {
        return $this->hasMany(BuffRequest::class, 'handled_by');
    }
}

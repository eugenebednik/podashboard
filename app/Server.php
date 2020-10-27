<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Server extends Model
{
    protected $table = 'servers';

    protected $fillable = [
        'snowflake',
        'name',
        'is_active',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buffRequests() : HasMany
    {
        return $this->hasMany(BuffRequest::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users() : HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function administrators() : BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function onDuty() : HasOne
    {
        return $this->hasOne(OnDuty::class);
    }

    /**
     * @return bool
     */
    public function hasUserOnDuty() : bool
    {
        return isset($this->onDuty) && $this->onDuty->user;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isUserOnDuty(User $user) : bool
    {
        return $this->hasUserOnDuty() && $this->onDuty->user->id === $user->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allowedRoles() : HasMany
    {
        return $this->hasMany(AllowedRole::class);
    }
}

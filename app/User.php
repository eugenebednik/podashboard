<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
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
        'name', 'email', 'discord_id', 'api_token'
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
     * @return BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * @return bool
     */
    public function administratedServers() : BelongsToMany
    {
        return $this->belongsToMany(Server::class);
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
     * Relationship between the user and their Alliance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function alliance() : BelongsTo
    {
        return $this->belongsTo(Alliance::class);
    }

    /**
     * @param Server $server
     *
     * @return mixed
     */
    public function isAdminOfServer(Server $server)
    {
        return $this->administratedServers->contains($server);
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

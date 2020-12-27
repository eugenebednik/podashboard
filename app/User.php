<?php

namespace App;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

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
    public function server() : BelongsTo
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
     * @param Server $server
     *
     * @return bool
     */
    public function isAdminOfServer(Server $server) : bool
    {
        return $this->administratedServers->contains($server);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function onDuty() : HasOne
    {
        return $this->hasOne(OnDuty::class);
    }

    /**
     * @return HasMany
     */
    public function logs() : HasMany
    {
        return $this->hasMany(POLog::class);
    }

    /**
     * @param Server $server
     * @return bool
     */
    public function isOnServerDuty(Server $server) : bool
    {
        return isset($this->onDuty) && $this->onDuty->server->id === $server->id;
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

    /**
     * @param Server $server
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getAverageTimePerDuty(Server $server) : string
    {
        $avg = $this->logs()
            ->select(DB::raw("AVG(TIME_TO_SEC(TIMEDIFF(logged_in_at, logged_out_at))) AS timediff"))
            ->where('server_id', $server->id)
            ->get();

        return CarbonInterval::seconds((int)$avg[0]->timediff)->cascade()->forHumans();
    }

    /**
     * @param Server $server
     *
     * @return string
     */
    public function getTotalTimeSpentServing(Server $server) : string
    {
        $logs = $this->logs()
            ->select(DB::raw('TIME_TO_SEC(TIMEDIFF(logged_in_at, logged_out_at)) AS timediff'))
            ->where('server_id', $server->id)
            ->get();

        $totalSeconds = 0;

        foreach ($logs as $log) {
            $totalSeconds += abs((int)$log->timediff);
        }

        $diff = now()->addSeconds($totalSeconds);

        return $diff->longAbsoluteDiffForHumans(now());
    }
}

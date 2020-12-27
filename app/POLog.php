<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class POLog extends Model
{
    protected $table = 'po_logs';

    protected $fillable = [
        'logged_in_at',
        'logged_out_at',
    ];

    public $timestamps = false;

    /**
     * The server of the log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * The user of the log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

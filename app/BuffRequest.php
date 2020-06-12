<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuffRequest extends Model
{
    protected $table = 'buff_requests';

    protected $fillable = [
        'user_name',
        'discord_snowflake',
        'request_type',
        'outstanding',
        'is_alt_request',
        'alt_name',
    ];

    /**
     * Relationship between the buff request and the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    /**
     * Relationship between this and the request type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requestType()
    {
        return $this->belongsTo(RequestType::class);
    }
}

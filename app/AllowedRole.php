<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllowedRole extends Model
{
    protected $table = 'allowed_roles';

    protected $fillable = [
        'role_id',
        'role_name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}

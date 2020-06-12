<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alliance extends Model
{
    protected $table = 'alliances';

    protected $fillable = [
        'name',
    ];

    /**
     * Relationship between the Alliance and its users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'alliance_id');
    }
}

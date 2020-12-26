<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    protected $table = 'request_types';

    protected $fillable = ['name'];

    /**
     * Buff requests belonging to this request type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests()
    {
        return $this->hasMany(BuffRequest::class);
    }
}

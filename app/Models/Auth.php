<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'credentials'
    ];

    /**
     * Credentials relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function credentials()
    {
        return $this->morphTo();
    }
}

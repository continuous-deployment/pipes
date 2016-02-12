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
        'type',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'credentials',
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

    /**
     * Checks whether this auth uses a key as its authentication
     *
     * @return bool
     */
    public function isKeyAuthentication()
    {
        if ($this->credentials instanceof AuthKey) {
            return true;
        }

        return false;
    }

    /**
    * Checks whether this auth uses a username and password as its
    * authentication
    *
    * @return bool
    */
    public function isAccountAuthentication()
    {
        if ($this->credentials instanceof AuthAccount) {
            return true;
        }

        return false;
    }
}

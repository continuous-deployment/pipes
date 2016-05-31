<?php

namespace Pipes\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Pipes\Models\Auth $auth Auth model related to this host
 */
class Host extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'host',
        'port',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'auth',
    ];

    /**
     * Auth relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function auth()
    {
        return $this->belongsTo('Pipes\Models\Auth');
    }
}

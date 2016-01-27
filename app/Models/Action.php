<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'pipeable'
    ];

    /**
     * All the conditions that will run if this condition passes
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function pipeable()
    {
        return $this->morphTo();
    }
}

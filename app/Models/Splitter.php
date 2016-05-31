<?php

namespace Pipes\Models;

use Illuminate\Database\Eloquent\Model;

class Splitter extends Model
{
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'splits'
    ];

    /**
     * All the conditions that will run if this condition passes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function splits()
    {
        return $this->hasMany('Pipes\Models\Split');
    }
}

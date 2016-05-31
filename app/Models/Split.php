<?php

namespace Pipes\Models;

use Illuminate\Database\Eloquent\Model;

class Split extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'splitter_pipeables';

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

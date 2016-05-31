<?php

namespace Pipes\Models;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'field',
        'operator',
        'value'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'successPipeable',
        'failurePipeable'
    ];

    /**
     * All the conditions that will run if this condition passes
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function successPipeable()
    {
        return $this->morphTo('success_pipeable', null, 'success_pipeable_id');
    }

    /**
    * All the conditions that will run if this condition fails
    *
    * @return \Illuminate\Database\Eloquent\Relations\MorphTo
    */
    public function failurePipeable()
    {
        return $this->morphTo('failure_pipeable', null, 'failure_pipeable_id');
    }
}

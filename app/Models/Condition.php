<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    /**
     * All the conditions that will run if this condition passes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function successConditions()
    {
        return $this->hasMany('App\Models\Condition', 'success_condition_id', 'id');
    }

    /**
    * All the conditions that will run if this condition fails
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function failureConditions()
    {
        return $this->hasMany('App\Models\Condition', 'failure_condition_id', 'id');
    }
}

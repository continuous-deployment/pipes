<?php

namespace Pipes\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'group',
        'url',
        'project_id',
        'host_id'
    ];

    /**
     * Conditions relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conditions()
    {
        return $this->hasMany('Pipes\Models\Condition');
    }
}

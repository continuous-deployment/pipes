<?php

namespace Pipes\Models;

use Pipes\Models\Project;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    /**
     * Project relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Starting pipe for this stream
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function pipeable()
    {
        return $this->morphTo();
    }
}

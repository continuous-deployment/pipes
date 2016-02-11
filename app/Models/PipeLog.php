<?php

namespace App\Models;

use App\Models\Stream;
use Illuminate\Database\Eloquent\Model;

class PipeLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'severity',
        'message',
        'output'
    ];

    /**
     * Stream relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    /**
     * Pipe related to this log
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function pipeable()
    {
        return $this->morphTo();
    }
}

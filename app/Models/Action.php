<?php

namespace Pipes\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Pipes\Models\Host $host Host model related to this action
 */
class Action extends Model
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
        'pipeable',
        'commands',
    ];

    /**
     * The next pipe to take after this pipes is processed
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function pipeable()
    {
        return $this->morphTo();
    }

    /**
     * The host related to this action
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function host()
    {
        return $this->belongsTo(Host::class);
    }

    /**
     * Commands relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commands()
    {
        return $this->hasMany('Pipes\Models\Command');
    }

    /**
     * Create and attach a new command to this action
     *
     * @param string $command Command to add
     *
     * @return \Pipes\Models\Command
     */
    public function addCommand($command)
    {
        $commandModel          = new Command();
        $commandModel->command = $command;
        $commandModel->save();

        $this->commands()->save($commandModel);
    }
}

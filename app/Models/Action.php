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
        'pipeable',
        'commands',
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

    /**
     * Commands relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commands()
    {
        return $this->hasMany('App\Models\Command');
    }

    /**
     * Create and attach a new command to this action
     *
     * @param string $command Command to add
     *
     * @return \App\Models\Command
     */
    public function addCommand($command)
    {
        $commandModel          = new Command();
        $commandModel->command = $command;
        $commandModel->save();

        $this->commands()->save($commandModel);
    }
}

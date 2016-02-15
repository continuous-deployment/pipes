<?php

namespace App\Models;

use foo;
use App\Pipeline\Pipe;
use App\Pipeline\Traveler\Traveler;
use Illuminate\Database\Eloquent\Model;

class TravelerProgress extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'travelers_progress';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'bag',
        'status'
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
     * Pipe the traveler is currently on
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function pipeable()
    {
        return $this->morphTo();
    }

    /**
     * Updates the progress to be at the start of the pipe
     *
     * @param  Traveler $traveler Traveler to store
     * @param  Pipe     $pipe     Pipe the traveler is currently processing
     *
     * @return void
     */
    public function startOfPipe(Traveler $traveler, Pipe $pipe)
    {
        if (!$this->exists) {
            $this->initialiseProgress();
        }

        $serializedBag = $this->getBagFromTraveler($traveler);

        $this->fill([
            'status' => 'Processing',
            'bag' => $serializedBag
        ]);
        $this->pipeable()->associate($pipe->getModel());

        $this->save();
    }

    /**
     * Gets a serialized clone of the bag on the traveler
     *
     * @param Traveler  $traveler Traveler to get bag from
     *
     * @return \App\Pipeline\Traveler\Bag
     */
    protected function getBagFromTraveler(Traveler $traveler)
    {
        $clonedBag = clone $traveler->bag;
        $clonedBag->serialize();
        $serializedBag = serialize($clonedBag);

        return $serializedBag;
    }


    /**
     * Updates the progress to be at the end of the pipe
     *
     * @param  Traveler $traveler Traveler to store
     * @param  Pipe     $pipe     Pipe the traveler is going to next
     *
     * @return void
     */
    public function endOfPipe(Traveler $traveler, Pipe $pipe)
    {
        if ($traveler->nextPipe !== null) {
            // Create new traveler progress (and thus new traveler) if this
            // isn't the first pipe to be processed.
            $progress = new TravelerProgress();
            $progress->initialiseProgress();
            $progress->stream()->associate($this->stream);
            $progress->status = 'Traveling';
            $progress->pipeable()->associate($pipe->getModel());
            $progress->save();
            $traveler->progress = $progress;

            return;
        }

        $this->status = 'Traveling';
        $this->pipeable()->associate($pipe->getModel());

        $this->save();
    }

    /**
     * Updates the progress with the end of the pipelien
     *
     * @param  Traveler $traveler Traveler to store
     *
     * @return void
     */
    public function endOfPipeline(Traveler $traveler)
    {
        $this->status = 'Finished';
        $this->bag = $this->getBagFromTraveler($traveler);
        $this->save();
    }

    /**
     * Does initial data needed for the progress
     *
     * @return void
     */
    public function initialiseProgress()
    {
        $faker = \Faker\Factory::create('en_GB');
        $this->name = $faker->firstName . ' ' . $faker->lastName;
    }
}

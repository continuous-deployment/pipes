<?php

use App\Models\Split;
use App\Models\Action;
use App\Models\Splitter;
use App\Models\Condition;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ExamplePipelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $firstCondition = new Condition([
            'type' => 'if',
            'field' => '',
            'operator' => '==',
            'value' => ''
        ]);
        $firstCondition->save();

        $splitter = new Splitter();
        $splitter->save();
        $firstSplit = new Split();
        $firstSplit->save();
        $secondSplit = new Split();
        $secondSplit->save();

        $secondCondition = new Condition([
            'type' => 'contains',
            'field' => '',
            'operator' => '==',
            'value' => ''
        ]);
        $secondCondition->save();

        $firstAction = new Action([
            'action' => 'npm install'
        ]);
        $firstAction->save();

        $secondAction = new Action([
            'action' => 'composer install'
        ]);
        $secondAction->save();

        $secondCondition->success_pipeable()->associate($secondAction)->save();
        $firstSplit->pipeable()->associate($secondCondition);

        $secondSplit->pipeable()->associate($firstAction);

        $splitter->splits()->save($firstSplit);
        $splitter->splits()->save($secondSplit);

        $firstCondition->success_pipeable()->associate($splitter)->save();
    }
}

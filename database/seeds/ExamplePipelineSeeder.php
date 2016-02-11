<?php

use App\Models\Action;
use App\Models\Project;
use App\Models\Condition;
use App\Models\Split;
use App\Models\Splitter;
use Illuminate\Database\Seeder;

class ExamplePipelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $firstCondition = new Condition(
            [
                'type'     => 'if',
                'field'    => 'event.type',
                'operator' => '==',
                'value'    => 'push',
            ]
        );
        $firstCondition->save();

        $splitter = new Splitter();
        $splitter->save();
        $firstSplit = new Split();
        $firstSplit->save();
        $secondSplit = new Split();
        $secondSplit->save();

        $secondCondition = new Condition(
            [
                'type'     => 'contains',
                'field'    => '',
                'operator' => '==',
                'value'    => '',
            ]
        );
        $secondCondition->save();

        $firstAction = new Action();
        $firstAction->save();
        $firstAction->addCommand('npm install');
        $firstAction->addCommand('npm run build');

        $secondAction = new Action();
        $secondAction->save();
        $secondAction->addCommand('composer install');

        $secondCondition->successPipeable()->associate($secondAction)->save();
        $firstSplit->pipeable()->associate($secondCondition);

        $secondSplit->pipeable()->associate($firstAction);

        $splitter->splits()->save($firstSplit);
        $splitter->splits()->save($secondSplit);

        $firstCondition->successPipeable()->associate($splitter)->save();

        $project = new Project();
        $project->name = 'example';
        $project->group = 'exampleGroup';
        $project->url = 'http://localhost/exampleGroup/example';
        $project->project_id = 2;
        $project->save();

        $project->conditions()->save($firstCondition);
    }
}

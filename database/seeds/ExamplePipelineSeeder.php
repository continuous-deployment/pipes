<?php

use Pipes\Models\Action;
use Pipes\Models\Project;
use Pipes\Models\Auth;
use Pipes\Models\AuthAccount;
use Pipes\Models\AuthKey;
use Pipes\Models\Condition;
use Pipes\Models\Host;
use Pipes\Models\Split;
use Pipes\Models\Splitter;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem as File;

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

        $firstAction = new Action([
            'type' => 'ssh',
        ]);
        $host = new Host([
            'host' => 'ssh',
            'port' => '22',
        ]);

        $auth                  = new Auth();
        $accountAuth           = new AuthAccount();
        $accountAuth->username = getenv('SSH_USERNAME');
        $accountAuth->password = getenv('SSH_PASSWORD');
        $accountAuth->save();

        try {
            $file = new File();
            $keyAuth           = new AuthKey();
            $keyAuth->username = 'root';
            $keyAuth->key = $file->get('.docker/builds/ssh/ssh_keys/id_rsa');
            $keyAuth->key_public = $file->get('.docker/builds/ssh/ssh_keys/id_rsa.pub');
            $keyAuth->save();
        } catch (Illuminate\Filesystem\FileNotFoundException $exception) {
            dump("SSH Key Not Found");
        }

        $auth->credentials()->associate($keyAuth);
        $auth->save();
        $host->auth()->associate($auth);
        $host->save();
        $firstAction->host()->associate($host);

        $firstAction->save();
        $firstAction->addCommand('touch testing');
        $firstAction->addCommand('ls');

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

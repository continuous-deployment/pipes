<?php

namespace App\Console\Commands\Registrars;

use App\GitLab\Hooks\GitLab;
use App\GitLab\Hooks\HookRegister;
use App\GitLab\Hooks\GitLabManager;
use Illuminate\Console\Command;

class GitLabRegistrar implements Registrar
{
    /**
     * Command instance
     * @var Command
     */
    protected $command;

    /**
     * GitLab Manager instance
     * @var GitLabManager
     */
    protected $gitlabManager;

    /**
     * Login/email to be used to authenticate with GitLab
     * @var string
     */
    protected $login = '';

    /**
     * Password to be used to authenticate with GitLab
     * @var string
     */
    protected $password = '';

    /**
     * Private token to used to access the GitLab api
     * @var string
     */
    protected $privateToken = '';

    /**
     * Constructor
     * @param Command $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
        $this->gitlabManager = new GitLabManager();
    }

    /**
     * Registers a new GitLab instance
     */
    public function register()
    {
        $host = $this->command->ask('What is the url of your GitLab instance? (https://git.example.com)');
        $authenticationType = $this->command->choice(
            'How would you like to authenticate with your GitLab?',
            ['Username/email and password', 'Private token', 'Both'],
            '2'
        );

        if ($authenticationType == 'Username/email and password') {
            $this->loginQuestions();
        } elseif ($authenticationType == 'Private token') {
            $this->privateTokenQuestions();
        } else {
            $this->loginQuestions();
            $this->privateTokenQuestions();
        }

        $numberOfGitLabs = count($this->gitlabManager->getInstances());

        $gitlab = new GitLab(
            $numberOfGitLabs + 1,
            $this->login,
            $this->password,
            $this->privateToken,
            $host
        );

        $this->gitlabManager->addInstance($gitlab);
    }

    /**
     * Questsions used to get the private token
     */
    protected function privateTokenQuestions()
    {
        $privateToken = $this->command->ask('What is the private token of the user?');

        $this->privateToken = $privateToken;
    }

    /**
     * Questions used for login
     */
    protected function loginQuestions()
    {
        $login = $this->command->ask('What is the username or email of the user?');
        $password = $this->command->secret('What is the password of the user? (hidden)');

        $this->login = $login;
        $this->password = $password;
    }

    /**
     * Called after the user has finished registering new services.
     */
    public function afterRegistration()
    {
        $register = $this->command->confirm(
            'Would you like to register system hooks?',
            true
        );

        if ($register) {
            $hookRegister = new HookRegister($this->gitlabManager);
            $hookRegister->registerSystemHooksOnInstances();
        }
    }

    /**
     * Update the env string with any new values
     * @param  string $envString
     * @return string
     */
    public function updateEnv($envString)
    {
        $envString = preg_replace('/GITLAB_.*\n\n?/', '', $envString);
        $instances = $this->gitlabManager->getInstances();

        for ($index = 0; $index < count($instances); $index++) {
            $gitlab = $instances[$index];
            $gitlabEnvString = $this->buildGitLabEnv($gitlab, $index);
            $envString .= $gitlabEnvString;
        }

        return $envString;
    }

    /**
     * Builds the env string from the GitLab instance
     * @param  GitLab  $gitlab
     * @param  integer $number
     * @return string
     */
    public function buildGitLabEnv(GitLab $gitlab, $number)
    {
        if ($number != 0) {
            $number = '_' . $number;
        } else {
            $number = '';
        }

        $values = [];
        $host = $gitlab->getHost();
        $username = $gitlab->getUsername();
        $password = $gitlab->getPassword();

        $values[] = 'GITLAB_URL' . $number . '=' . $host;
        $values[] = 'GITLAB_AUTH_USER' . $number . '=' . $username;
        $values[] = 'GITLAB_AUTH_PASS' . $number . '=' . $password;

        if (!$gitlab->hasAuthenticated()) {
            $privateToken = $gitlab->getPrivateTokenProperty();
            $values[] = 'GITLAB_AUTH_PRIVATE_TOKEN' . $number . '=' . $privateToken;
        }

        return implode(PHP_EOL, $values) . PHP_EOL . PHP_EOL;
    }
}

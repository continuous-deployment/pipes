<?php

namespace App\GitLab\Hooks;

use App\Hooks\GitLab\GitLab;

class GitLabManager
{
    /**
     * Instances of GitLab. Can be accessed via getInstances()
     * @var array
     */
    protected $instances = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->getInstancesFromEnv();
    }

    /**
     * Clears an instances on this manager
     */
    public function clearInstances()
    {
        $this->instances = [];
    }

    /**
     * Gets all GitLab instances from the env file.
     */
    public function getInstancesFromEnv()
    {
        $moreInstances = true;
        $envNumber     = '';

        while ($moreInstances) {
            $gitlab = new GitLab($envNumber);

            if (!$gitlab->configLoaded()) {
                $moreInstances = false;
            } else {
                $this->instances[] = $gitlab;

                if ($envNumber == '') {
                    $envNumber = 1;
                } else {
                    $envNumber++;
                }
            }
        }
    }

    /**
     * Gets all GitLab instances currently created
     * @return array
     */
    public function getInstances()
    {
        return $this->instances;
    }

    /**
     * Adds a GitLab instance to the array.
     * @param GitLab $gitlab
     */
    public function addInstance(GitLab $gitlab)
    {
        $this->instances[] = $gitlab;
    }

    /**
     * Send an Api request to all the instances
     * @param string $method
     * @param string $path
     * @param array  $formParams
     */
    public function sendApiRequestToInstances($method, $path, $formParams = [])
    {
        foreach ($this->instances as $instance) {
            $instance->sendApiRequest($method, $path, $formParams);
        }
    }
}

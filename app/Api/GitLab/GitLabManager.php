<?php

namespace App\Api\GitLab;

use App\Api\GitLab\GitLab;

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
     * @return void
     */
    public function clearInstances()
    {
        $this->instances = [];
    }

    /**
     * Gets all GitLab instances from the env file.
     * @return void
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
     * Accepts a Gitlab instance
     * @param GitLab $gitlab
     * @return void
     */
    public function addInstance(GitLab $gitlab)
    {
        $this->instances[] = $gitlab;
    }

    /**
     * Send an Api request to all the instances
     * Which API method to use
     * @param string $method
     * Which API path to send to
     * @param string $path
     * What form parameters to send
     * @param array  $formParams
     * @return void
     */
    public function sendApiRequestToInstances($method, $path, $formParams = [])
    {
        foreach ($this->instances as $instance) {
            $instance->sendApiRequest($method, $path, $formParams);
        }
    }
}

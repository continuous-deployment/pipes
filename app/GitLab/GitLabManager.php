<?php

namespace Pipes\GitLab;

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
     * @param  GitLab $gitlab accepts a gitlab instance
     * @return void
     */
    public function addInstance(GitLab $gitlab)
    {
        $this->instances[] = $gitlab;
    }

    /**
     * Host id to find GitLab instance related to
     *
     * @param integer $hostId
     *
     * @return GitLab|null
     */
    public function getInstanceByHostId($hostId)
    {
        /** @var \Pipes\GitLab\GitLab $instance */
        foreach ($this->instances as $instance) {
            if ($instance->getHostId() == $hostId) {
                return $instance;
            }
        }

        return null;
    }
}

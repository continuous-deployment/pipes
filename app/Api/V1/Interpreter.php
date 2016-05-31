<?php

namespace Pipes\Api\V1;

use Pipes\Api\V1\Transformers\Validators\PipelineValidator;
use Pipes\Api\V1\Transformers\Factory;

class Interpreter
{
    /**
     * Validator for the pipeline
     *
     * @var \Pipes\Api\V1\Transformers\Validators\PipelineValidator
     */
    protected $validator;

    /**
     * Factory to create transformers and validators
     *
     * @var \Pipes\Api\V1\Transformers\Factory
     */
    protected $factory;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->validator = new PipelineValidator();
        $this->factory = new Factory();
    }

    /**
     * Retrieves the error messages from the validator and transformers
     *
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->validator->getErrorMessages();
    }

    /**
     * Parses a pipeline request
     *
     * @param stdClass $data Data to interpret
     *
     * @return bool
     */
    public function parsePipelineRequest($data)
    {
        if (!$this->validator->validate($data)) {
            return false;
        }

        $this->parseData($data);

        return true;
    }

    /**
     * Parses the data of the request
     *
     * @param stdClass $data Data sent by the user
     */
    protected function parseData($data)
    {
        $type = $data->type;
        $transformer = $this->factory->make($type);
        $model = $transformer->transform($data);
        $relationships = $transformer->getNextPipesFromData($data);

        foreach ($relationships as $relationshipName => $relationshipData) {
            $relationshipIsArray = true;

            if (!is_array($relationshipData)) {
                $relationshipIsArray = false;
                $relationshipData = [$relationshipData];
            }

            $relationshipData = array_map(function ($data) {
                return $this->parseData($data);
            }, $relationshipData);

            if ($relationshipIsArray === false) {
                $relationshipData = array_pop($relationshipData);
            }

            $transformer->attachRelationship(
                $relationshipName,
                $model,
                $relationshipData
            );
        }

        return $model;
    }
}

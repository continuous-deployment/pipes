<?php

namespace App\Api\V1;

use App\Api\V1\Transformers\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag;

class Interpreter
{
    /**
     * Factory to create transformers and validators
     *
     * @var \App\Api\V1\Transformers\Factory
     */
    protected $factory;

    /**
     * Error messages of the interpreter
     *
     * @var array
     */
    protected $errorMessages = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->factory = new Factory();
    }

    /**
     * Add error messages to the current array of error messages
     *
     * @param string $key     Key to store messages against
     * @param array $messages Messages to store
     *
     * @return void
     */
    protected function addErrorMessage($key, $messages)
    {
        $messagesKey = $key . '.errors';

        if ($key == null) {
            $messagesKey = 'errors';
        }

        $this->errorMessages = Arr::add(
            $this->errorMessages,
            $messagesKey,
            $messages
        );
    }

    /**
     * Retrieves the currently set errorMessages.
     *
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * Parses a pipeline request
     *
     * @param  stdClass $data Data to interpret
     *
     * @return bool
     */
    public function parsePipelineRequest($data)
    {
        if (!isset($data->type)) {
            $this->errorMessages['pipeline'] =
                'The starting pipe must have a type.';

            return false;
        }

        if (!$this->validatePipelineRequest($data)) {
            return false;
        }

        return true;
    }

    /**
     * Validates the pipeline request
     *
     * @param  stdClass $data Data to interpret
     * @param  string   $key  Key to add the errors to
     *
     * @return bool
     */
    public function validatePipelineRequest($data, $key = '')
    {
        if ($data === null) {
            $this->addErrorMessage(
                $key,
                ['Unable to parse request data.']
            );

            return false;
        }

        $passes = true;

        if (!isset($data->type)) {
            $this->addErrorMessage(
                $key,
                ['Pipe must have a type.']
            );

            return false;
        }

        $validator = $this->factory->makeValidator($data->type);

        if ($validator === null) {
            $this->addErrorMessage(
                $key,
                ['Validator for ' . $data->type . ' does not exist.']
            );

            return false;
        }

        $validator->setData($data);

        if ($validator->fails()) {
            $this->addErrorMessage(
                $key,
                $validator->getErrorMessages()->toArray()
            );

            return false;
        }

        $passes = $this->validateRelationships($data, $key);

        return $passes;
    }

    /**
     * Validate the relationships of the given data
     *
     * @param mixed $data Data to validate
     * @param string $key Key to store any error messages against
     *
     * @return bool
     */
    protected function validateRelationships($data, $key)
    {
        $passes = true;

        $transformer = $this->factory->make($data->type);

        if ($transformer === null) {
            $this->addErrorMessage(
                $key,
                ['Transformer for ' . $data->type . ' does not exist.']
            );

            return false;
        }

        $relationships = $transformer->getNextPipesFromData($data);

        foreach ($relationships as $relationshipName => $relationship) {
            $messagesKey = $relationshipName;

            if ($key != '') {
                $messagesKey = $key . '.' . $messagesKey;
            }

            if (is_array($relationship)) {
                foreach ($relationship as $key => $item) {
                    $messagesKey = $messagesKey . '.' . $key;

                    if (!$this->validateSingleRelationshipItem(
                        $item,
                        $messagesKey
                    )) {
                        $passes = false;
                    }
                }

                continue;
            }

            $this->validateSingleRelationshipItem($relationship, $messagesKey);
        }

        return $passes;
    }


    /**
     * Validate relationship data
     *
     * @param mixed $item        Item to validate
     * @param string $messagesKey The key to use when storing error messages
     *
     * @return bool
     */
    protected function validateSingleRelationshipItem($item, $messagesKey)
    {
        $result = $this->validatePipelineRequest(
            $item,
            $messagesKey
        );

        if (!$result) {
            return false;
        }
    }
}

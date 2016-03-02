<?php

namespace App\Api\V1\Transformers;

abstract class Validator
{
    /**
     * Validation rules for the attributes
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Validation messages for the attributes
     *
     * @var array
     */
    protected $messages = [];

    /**
    * Validation rules for the relationships
    *
    * @var array
    */
    protected $relationshipRules = [];

    /**
    * Validation messages for the relationships
    *
    * @var array
    */
    protected $relationshipMessages = [];

    /**
     * Data to validation against
     *
     * @var stdClass
     */
    protected $data;

    /**
     * Any error messages from the validation rules
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errorMessages;

    /**
     * Sets the data to use.
     *
     * @param stdClass $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Retrieves the currently set error messages.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * Checks if the data given passes the validation rules
     *
     * @return bool
     */
    public function passes()
    {
        $attributes = [];

        if (isset($this->data->attributes)) {
            $attributes = (array)$this->data->attributes;
        }

        /** @var \Illuminate\Validation\Factory $validationFactory */
        $validationFactory = app('validator');
        $attrValidator = $validationFactory->make(
            $attributes,
            $this->rules,
            $this->messages
        );
        $attrPasses = $attrValidator->passes();

        $relationValidator = $validationFactory->make(
            (array)$this->data,
            $this->relationshipRules,
            $this->relationshipMessages
        );
        $relationPasses = $relationValidator->passes();

        $mergedMessages = $attrValidator->messages()->merge(
            $relationValidator->messages()->getMessages()
        )
        ->getMessageBag();

        $this->errorMessages = $mergedMessages;

        return $attrPasses && $relationPasses;
    }

    /**
     * Checks if the data given fails the validation rules
     *
     * @return bool
     */
    public function fails()
    {
        return !$this->passes();
    }
}

<?php

namespace Pipes\Api\V1\Transformers\Validators;

use Pipes\Api\V1\Transformers\Validator;

class ActionValidator extends Validator
{
    /**
     * Validation rules for the attributes
     *
     * @var array
     */
    protected $rules = [];

    /**
    * Validation rules for the relationships
    *
    * @var array
    */
    protected $relationshipRules = [
        'next'     => 'model',
        'commands' => 'array',
        'host'     => 'model',
    ];
}

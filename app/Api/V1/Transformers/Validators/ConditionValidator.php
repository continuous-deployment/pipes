<?php

namespace App\Api\V1\Transformers\Validators;

use App\Api\V1\Transformers\Validator;

class ConditionValidator extends Validator
{
    /**
     * Validation rules for the attributes
     *
     * @var array
     */
    protected $rules = [
        'type'     => 'required',
        'field'    => 'required',
        'operator' => 'required',
        'value'    => 'required',
    ];

    /**
    * Validation rules for the relationships
    *
    * @var array
    */
    protected $relationshipRules = [
        'success' => 'model',
        'failure' => 'model',
    ];
}

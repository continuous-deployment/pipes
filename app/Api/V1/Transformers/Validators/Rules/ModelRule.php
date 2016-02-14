<?php

namespace App\Api\V1\Transformers\Validators\Rules;

class ModelRule
{
    /**
     * Checks if the given data has a type and that type is a model that
     * exists.
     *
     * @param  string    $attribute  The attribute name
     * @param  mixed     $value      The value of the attribute
     * @param  array     $parameters Any parameters passed into rule
     * @param  Validator $validator  The validator instance
     *
     * @return boolean
     */
    public function validate($attribute, $value, $parameters, $validator)
    {
        if (!isset($value->type)) {
            return false;
        }

        $modelClassName = '\App\Models\\' . title_case($value->type);

        if (!class_exists($modelClassName)) {
            return false;
        }

        return true;
    }
}

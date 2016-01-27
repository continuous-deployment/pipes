<?php

namespace App\Pipeline;

use ArrayAccess;
use Illuminate\Support\Arr;
use ReflectionClass;

class Traveler
{
    /**
    * Array of items the traveler is holding
     *
     * @var array
     */
    protected $items;

    /**
     * Have a look at one of the travelers items
     *
     * @param  string $key Dot notation key
     * @return mixed
     */
    public function lookAt($key)
    {
        $array = $this->items;

        if ($key == null) {
            return null;
        }

        $key = strtolower($key);

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) && !($array instanceof ArrayAccess)) {
                return null;
            }

            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Give an item to the traveler
     *
     * @param  mixed  $value Value to assoicate to key
     * @param  string $key   Dot notation key, default: null
     * @return void
     */
    public function give($value, $key = null)
    {
        if ($key === null) {
            $class = new ReflectionClass($value);
            $key   = snake_case($class->getShortName());
        }

        $this->items = Arr::add($this->items, strtolower($key), $value);
    }
}

<?php

namespace App\Pipeline\Traveler;

use Illuminate\Support\Arr;

class Bag
{
    /**
    * Array of items the traveler is holding
     *
     * @var array
     */
    protected $items;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = [];
    }

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
            if ($value === null) {
                return;
            }

            if (!is_array($value)) {
                $class = new ReflectionClass($value);
                $key   = snake_case($class->getShortName());
            } else {
                foreach ($value as $key => $item) {
                    Arr::set($this->items, $key, $item);
                }

                return;
            }
        }

        $this->items = Arr::set($this->items, strtolower($key), $value);
    }
}

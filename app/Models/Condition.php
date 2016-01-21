<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    public function conditions()
    {
        return $this->hasMany('App\Models\Condition');
    }
}

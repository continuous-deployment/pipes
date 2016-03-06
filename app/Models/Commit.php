<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'commit_id',
        'message',
        'url',
        'timestamp',
        'author_name',
        'author_email',
        'branch',
        'project_id',
    ];
}

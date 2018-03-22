<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['type', 'date', 'content', 'created_at', 'updated_at'];
}

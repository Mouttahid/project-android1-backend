<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $fillable = [
        'name', 'comment', 'deadline','progress', 'state', 'user_id'
    ];

    public function comments()
    {
        return $this->belongsTo('App\User',"user_id");
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'poll_id', 
        'answer_id', 
        'ip', 
        'country', 
        'language',
        'hardware_concurrency',
        'timezone',
        'platform'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'language',
        'hardware_concurrency',
        'timezone',
        'platform'
    ];

    public function poll()
    {
        return $this->belongsTo('App\Poll');
    }

    public function answer()
    {
        return $this->belongsTo('App\PollAnswer');
    }
}

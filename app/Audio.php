<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    /**
     * Get the user that owns the recording.
     */
	public function user()
	{
	    return $this->belongsTo('App\User');
	}
}

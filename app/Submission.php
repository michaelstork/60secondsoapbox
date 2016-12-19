<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
	
    /**
     * Get the user that owns the submission.
     */
	public function user()
	{
	    return $this->belongsTo('App\User');
	}
}

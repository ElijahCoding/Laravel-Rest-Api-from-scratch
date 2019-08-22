<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [];

    protected $dates = ['birthday'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

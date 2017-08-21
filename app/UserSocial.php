<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSocial extends Model
{
    protected $table = 'users_social';

    protected $fillable = ['service_id', 'service'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

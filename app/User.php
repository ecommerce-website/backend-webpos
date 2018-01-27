<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class User extends Model
{
     protected $fillable = ['user_name', 'user_id'];
    public function user(){
        return $this->belongsto('App\User');
    }
}

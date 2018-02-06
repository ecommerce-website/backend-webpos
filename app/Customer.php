<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{
    protected $fillable = ['customer_id','customer_group_id'];
    public function customer(){
        return $this->belongsto('App\Customer');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Customers extends Model
{
    protected $table = "customers";
    protected $fillable = ['customer_id','customer_group_id'];
    public function customer(){
        return $this->belongsto('App\Customer');
    }
}

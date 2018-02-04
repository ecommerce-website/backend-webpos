<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchCustomer extends Model
{
    protected $table = 'customer';
    public function membership(){
    	return $this => hasMany('App\Membership','customer_group_id','customer_id');
    }
}

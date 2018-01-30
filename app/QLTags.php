<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QLTags extends Model
{
    //
    protected $table = 'ql_tags';
    public function tags() {
    	return $this->belongsTo('App\Tags','ql_tags_tag_id','tag_id');
    }
}

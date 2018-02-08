<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QLTags extends Model
{
    //
    protected $table = 'ql_tags';
    protected $primaryKey = 'ql_tags_id';
    public function tags() {
    	return $this->belongsTo('App\Tags','ql_tags_tag_id','tag_id');
    }
}

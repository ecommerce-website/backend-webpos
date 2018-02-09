<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    //
    protected $table = 'tags';
    protected $fillable = ['tag_name'];
    public function qltags() {
    	return $this->belongsToMany('App\QLTags','ql_tags_tag_id','tag_id');
    }
}

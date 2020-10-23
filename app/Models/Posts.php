<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo('App\users' ,'id' , 'userid');
    }

    public function likes(){
        return $this->hasMany('App\likes' , 'postid' , 'id');
    }

    public function postStatistics(){
        return $this->hasOne('App\post_statistics' ,'postid' , 'id');
    }

    public function comments(){
        return $this->hasMany('App\comment' , 'postid' , 'id');
    }

}

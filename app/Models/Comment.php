<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Comment extends Model
{
   
    use HasFactory;
    protected $table = "comment"; 
    public $timestamps = false;
    protected $fillable = [
        'userid' , 'postid' , 'text' , 'datetime'
    ];
    
    private function user(){
        return $this->belongsTo('App\Models\User' ,'userid' , 'id');
    }
    public function jsonify(){
        $_user = $this->user()->first();

        return [
            'userInfo'=>$_user->jsonify(),
            'commentInfo'=>['id'=>$this->id , 'text'=>$this->text , 'datetime'=>$this->datetime]
        ];
    }
}

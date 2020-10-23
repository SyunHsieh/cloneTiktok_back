<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
class Posts extends Model
{   protected $table = "posts"; 
    public $timestamps = false;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text','videourl','createdate','userid'
    ];

    public function user(){
        return $this->belongsTo('App\Models\user' ,'userid' , 'id');
    }

    public function likes(){
        return $this->hasMany('App\Models\likes' , 'postid' , 'id');
    }

    public function postStatistics(){
        return $this->hasOne('App\Models\Post_statistics' ,'postid' , 'id');
    }

    public function comments(){
        return $this->hasMany('App\Models\comment' , 'postid' , 'id');
    }

    public static function createPost($user , $text , $videourl){
        
        $post = Posts::create([
            'text' => $text , 
            'videourl' => $videourl,
            'userid' => $user->id,
            'createdate' => new DateTime(),
        ]);
        $flag = FALSE;
        $id = NULL;
        if($post){
          $flag = TRUE;
          $id = $post->id;
        }
        return ['flag'=>$flag , 'id'=>$id];
        
    }

    ///diff with user.getUserPosts , this function will search all users' posts.
    public static function getPosts($count , $offset , $reader){
        $posts = Posts::orderby('createdate' , 'desc')->skip($offset)->take($count)->get();

        $ret = $posts->map(function($post) use ($reader) {
                return $post->jsonify();
        });
        return $ret;
    }
    public function jsonify($reader = NULL){
        return [
            'userInfo'=>[
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'postInfo'=>[
                 'id' => $this->id,
                 'videlurl' =>str_replace('gs:/',env("GCS_HOST","https://storage.googleapis.com"),$this->videourl),
                 'text' => $this->text,
                 'likesCount' =>$this->postStatistics->likescount,
                 'commentsCount' =>$this->postStatistics->commentscount,
            ],
            'readersInfo'=>[
                 'liked'=>$reader ? $reader->likes()->where('postid',$this->id)->first() !== NULL:FALSE,
                 'following'=>$reader ? $reader->followings()->where('targetuserid' , $this->user->id)->first() !== NULL :FALSE
            ]
        ];
    }

}

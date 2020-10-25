<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use DB;
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

    private function user(){
        return $this->belongsTo('App\Models\user' ,'userid' , 'id');
    }
    private function likes(){
        return $this->hasMany('App\Models\likes' , 'postid' , 'id');
    }
    private function postStatistics(){
        return $this->hasOne('App\Models\Post_statistics' ,'postid' , 'id');
    }
    private function comments(){
        return $this->hasMany('App\Models\comment' , 'postid' , 'id');
    }


    public function increseCommentsCount(){
        $this->postStatistics()->update([
            'commentscount'=>DB::raw('commentscount+1'),
            ]);
    }
    public function increseLikesCount(){
        $this->postStatistics()->update([
            'likescount'=>DB::raw('likescount+1'),
            ]);
    }
    public function decreseCommentsCount(){
        $this->postStatistics()->update([
            'commentscount'=>DB::raw('commentscount-1'),
            ]);
    }
    public function decreseLikesCount(){
        $this->postStatistics()->update([
            'likescount'=>DB::raw('likescount-1'),
            ]);
    }
    public function getCommentsCount(){
        return $this->postStatistics()->first()->commentscount;
    }   
    public function getLikesCount(){
        return $this->postStatistics()->first()->likescount;
    }
    public function getComments($count , $offset){
        $comments = $this->comments()->orderby('datetime','desc')->skip($offset)->take($count)->get();
        $ret = $comments->map(function($comment) {
            return $comment->jsonify();
        });
        return $ret;
    }

    public static function getPost($postid){
        return Posts::where('id',$postid)->first();
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
    public static function searchPost($textList,$count , $offset){
        $query = Posts::orderby('createdate','desc');

        foreach($textList as $text){
            $query->orwhere('text','like','%'.$text.'%');
        }

        $posts = $query->skip($offset)->take($count)->get();
        return $posts;
    }
    ///diff with user.getUserPosts , this function will search all users' posts.
    public static function getPosts($count , $offset , $reader){
        $posts = Posts::orderby('createdate' , 'desc')->skip($offset)->take($count)->get();

        return $posts;
    }
    public function jsonify($reader = NULL){
        $author = $this->user()->first();
        $statistics = $this->postStatistics()->first();
        return [
            'userInfo'=>$author->jsonify(),
            'postInfo'=>[
                 'id' => $this->id,
                 'videourl' =>str_replace('gs:/',env("GCS_HOST","https://storage.googleapis.com"),$this->videourl),
                 'text' => $this->text,
                 'likesCount' =>$statistics->likescount,
                 'commentsCount' =>$statistics->commentscount,
            ],
            'readersInfo'=>[
                 'liked'=>$reader ? $this->likes()->where('userid',$reader->id)->first() !== NULL:FALSE,
                 'following'=>$reader ? $reader->isFollowing($author):FALSE
            ]
        ];
    }

}

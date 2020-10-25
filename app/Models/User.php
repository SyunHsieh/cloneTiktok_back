<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use DateTime;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'account',
        'salt',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'salt'
    ];
    public $timestamps = false;
    private function posts(){
        return $this->hasMany('App\Models\posts','userid','id');
    }

    private function followings(){
        return $this->hasMany('App\Models\following','userid','id');
    }

    private function comments(){
        return $this->hasMany('App\Models\comment' , 'userid' , 'id');
    }
    private function likes(){
        return $this->hasMany('App\Models\likes', 'userid','id');
    }
    
    public function setPostLike($post , $like){
        $curLike = $this->likes()->where('postid',$post->id)->first();
        //當 設定值與 實際值不同時才需要update DB.
        if($curLike===NULL &&$like){
            $this->likes()->create(['postid'=>$post->id,'datetime'=>new DateTime()]);
            $post->increseLikesCount();
        }
        elseif($curLike!==NULL && !$like){
            $curLike->delete();
            $post->decreseLikesCount();
        }
    }
    public function createComment($post , $comment){
        $this->comments()->create([
            'postid'=>$post->id ,
            'text' =>$comment,
            'datetime' => new DateTime()
            ]);
            $post->increseCommentsCount();
    }
    public function deleteComment($commentId){
        $comment = $this->comments()->where('id' , $commentId)->first();

        // NULL MEANS comment not exist or not comment's owner.
        if($comment)
        {
            $comment->delete();
            return TRUE;
        }
        else
            return FALSE;
            
        
    }
    public function getUserPosts($count  , $offset,$reader=NULL ){
        $posts =  $this->posts()->orderby('createdate','desc')->skip($offset)->take($count )->get();
        
        $retData = $posts->map(function($post) use ($reader){
            return $post->jsonify($reader);
        });
        return $retData;
    }
    public function isFollowing($targetuser){
        $ret = $this->followings()->where('targetuserid',$targetuser->id)->first();
        return $ret !== NULL;
    }



    public static function createUser(string $account , string $passwordhash , string $salt , string $name ){
        // return $userimagepath;
        $user = User::create(
            ['account' => $account,
            'password' => $passwordhash,
            'salt' => $salt,
            'name' => $name,]
        );
        if($user === null)
            return FALSE;
        else
            return TRUE;
    }
    public static function isAccountExists(string $account){
        $user = User::where('account',$account)->first();
        // return $user;
        if($user === null)
            return FALSE;
        else
            return TRUE;
    }
    public static function getUser($userid){
        $user = User::where('id',$userid)->first();
        return $user;
    }
    public function jsonify(){
        return ['id'=>$this->id ,'name'=>$this->name];
    }
}

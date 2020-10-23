<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


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
    // SHOULD CHECK ACCOUNT EXISTS BEFORE USING isAccountExists();
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
    public function getUserPosts($count  , $offset,$reader=NULL ){
        $posts =  $this->posts()->orderby('createdate','desc')->skip($offset)->take($count )->get();
        
        $retData = $posts->map(function($post) use ($reader){
            return $post->jsonify($reader);
            // return [
            //     'userInfo'=>[
            //         'id' => $post->user->id,
            //         'name' => $post->user->name,
            //     ],
            //     'postInfo'=>[
            //          'id' => $post->id,
            //          'videlurl' =>$post->videourl,
            //          'text' => $post->text,
            //          'likesCount' =>$post->postStatistics->likescount,
            //          'commentsCount' =>$post->postStatistics->commentscount,
            //     ],
            //     'readersInfo'=>[
            //          'liked'=>$reader ? $reader->likes()->where('postid',$post->id)->first() !== NULL:999,
            //          'following'=>$reader ? $reader->followings()->where('targetuserid' , $post->user->id)->first() !== NULL :888
            //     ]
            // ];
        });
        return $retData;
    }
    public function posts(){
        return $this->hasMany('App\Models\posts','userid','id');
    }

    public function followings(){
        return $this->hasMany('App\Models\following','userid','id');
    }

    public function comments(){
        return $this->hasMany('App\Models\comment' , 'userid' , 'id');
    }
    public function likes(){
        return $this->hasMany('App\Models\likes', 'userid','id');
    }

}

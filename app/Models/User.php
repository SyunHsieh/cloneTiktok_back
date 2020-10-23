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
    public function posts(){
        return $this->hasMany('App\posts','userid','id');
    }

    public function followings(){
        return $this->hasMany('App\following','userid','id');
    }

    public function comments(){
        return $this->hasMany('App\comment' , 'userid' , 'id');
    }
    public function likes(){
        return $this->hasMany('App\likes', 'userid','id');
    }

}

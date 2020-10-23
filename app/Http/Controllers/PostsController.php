<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts as PostsModel;
use App\Models\User as UserModel;
use App\Models\Post_statistics as Post_StatisticsModel;
class PostsController extends Controller
{
    //

    public static function CreatePost($user , $text , $videourl){
       
   
        if($user === NULL)
            return FALSE;
        
        
        $postRet =  PostsModel::createPost($user , $text , $videourl);
        
        if($postRet['flag']){
            //create post -  statistics 
            return Post_StatisticsModel::createPostStatistic($postRet['id']);
        }
            


    }
    public static function GetUserPosts($req,$userid ,$reader){
       
        $user = UserModel::getUser($userid);

        if($user === NULL or $reader === NULL)
            return 'READER OR USER IS NULL';
        $count = intval($req->count);
        $offset = intval($req->offset);

        $ret = $user->getUserPosts($count,$offset,$reader);


        return $ret;
    }

    // diff with GetUserPosts , this function will search all users' posts.
    public static function GetPosts($req , $reader){
        $count = intval($req->count);
        $offset = intval($req->offset);

        $ret = PostsModel::getPosts($count , $offset , $reader);

        return $ret;
    }
}

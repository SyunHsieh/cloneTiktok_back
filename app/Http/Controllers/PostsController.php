<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts as PostsModel;
use App\Models\User as UserModel;
use App\Models\Post_statistics as Post_StatisticsModel;

use App\Responses\Response;
use App\Responses\ResponseType;
use App\Responses\ResponseMsg;

use App\Http\Controllers\GCSController;

class PostsController extends Controller
{
    //

    public static function CreatePost($user , $text,$source ){
        $resType = ResponseType::CreatePost;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;
        $videourl = '';
        
        //reader not login 
        if($user === NULL){
            $resMsg = ResponseMsg::NotLogin;
            $res = Response::GetResponseData($resType , $resMsg , $resData);
            return $res;
        }

        
        //upload video to GCS.
        $bucketname = env('GCS_BUCKET_NAME','clonetiktok');
        $gcsUploadRet = GCSController::UploadObject($bucketname ,GCSController::genRandomStr(),$source,'mp4', TRUE , TRUE);
        
        if(!$gcsUploadRet['flag'])
        {
            ///Upload video to GCS failed.
            $resMsg = ResponseMsg::CreatePostFailed;
            $res = Response::GetResponseData($resType , $resMsg , $resData);
            return $res;
        }


        $videourl = $gcsUploadRet['uri'];
        $postRet =  PostsModel::createPost($user , $text , $videourl);
        
        //Create posts data
        if(!$postRet['flag']){
            ///Create posts failed
            $resMsg = ResponseMsg::CreatePostFailed;
            $res = Response::GetResponseData($resType , $resMsg , $resData);
            return $res;
        }
        //create post statistics 
        if(!Post_StatisticsModel::createPostStatistic($postRet['id']))
            $resMsg = ResponseMsg::CreatePostFailed;
        else
            $resData = ['postid'=>$postRet['id']];

        $res = Response::GetResponseData($resType , $resMsg , $resData);
        return $res;
        
    }

    public static function GetUserPosts($req,$userid ,$reader){
        $resType = ResponseType::GetUserPosts;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;

        //check login
        $user = UserModel::getUser($userid);
        if($reader === NULL)
        {
            $resMsg = ResponseMsg::NotLogin;
            $res = Response::GetResponseData($resType , $resMsg , $resData);
            return $res;
        }

        //CHECK USER EXISTS.
        if($user === NULL ){
            $resMsg = ResponseMsg::GetUserPostsFailed;
            $res = Response::GetResponseData($resType , $resMsg , $resData);
            return $res;
        }
        $count = intval($req->count);
        $offset = intval($req->offset);

        $resData = $user->getUserPosts($count,$offset,$reader);


        $res = Response::GetResponseData($resType , $resMsg , $resData);
        return $res;
    }

    // diff with GetUserPosts , this function will search all users' posts.
    public static function GetPosts($req , $reader){
        $resType = ResponseType::GetPosts;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;
        if($reader === NULL)
        {   
            // $resMsg = ResponseMsg::NotLogin;
            // $res = Response::GetResponseData($resType , $resMsg , $resData);
            // return $res;
        }
        $count = intval($req->count);
        $offset = intval($req->offset);

        $ret = PostsModel::getPosts($count , $offset , $reader);

        
        $resData =$ret;
        $res = Response::GetResponseData($resType , $resMsg , $resData);
        return $res;

    }
}

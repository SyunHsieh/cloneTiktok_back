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

use Auth;
use Validator;
class PostsController extends Controller
{
    //

    public static function CreatePost(Request $req  ){
        $resType = ResponseType::CreatePost;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;

        $validator = Validator::make($req->all(),[
            'text'=>'required',
            'file'=>'required',
            ]);

        if($validator->fails()){
            $resMsg = ResponseMsg::RequestDataInvalid;
            return Response::GetResponseData($resType , $resMsg , $resData);
        }

        $videourl = '';
        $user = Auth::user();
        $text = $req['text'];
        $source =$req['file'];
        

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

    public static function GetUserPosts(Request $req,$userid ){
        
        $resType = ResponseType::GetUserPosts;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;

        $validator = Validator::make($req->all(),[
            'count'=>'required',
            'offset'=>'required',
            ]);

        if($validator->fails()){
            $resMsg = ResponseMsg::RequestDataInvalid;
            return Response::GetResponseData($resType , $resMsg , $resData);
        }

        $reader = Auth::user();
        $userid = intval($userid);

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
    public static function GetPosts(Request $req ){
        $resType = ResponseType::GetPosts;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;

        $validator = Validator::make($req->all(),[
            'count'=>'required',
            'offset'=>'required',
            ]);

        if($validator->fails()){
            $resMsg = ResponseMsg::RequestDataInvalid;
            return Response::GetResponseData($resType , $resMsg , $resData);
        }

        $reader = Auth::user();
        if($reader === NULL)
        {   
            $resMsg = ResponseMsg::NotLogin;
            $res = Response::GetResponseData($resType , $resMsg , $resData);
            return $res;
        }
        $count = intval($req->count);
        $offset = intval($req->offset);

        $posts = PostsModel::getPosts($count , $offset , $reader);

        
        $resData =$posts->map(function($post) use($reader){
            return $post->jsonify($reader);
        });

        $res = Response::GetResponseData($resType , $resMsg , $resData);
        return $res;

    }

    public static function SearchPost(Request $req){
        $resType = ResponseType::SearchPost;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;

        $validator = Validator::make($req->all(),[
            'text'=>'required',
            'count'=>'required',
            'offset'=>'required'
            ]);

        if($validator->fails()){
            $resMsg = ResponseMsg::RequestDataInvalid;
            return Response::GetResponseData($resType , $resMsg , $resData);
        }

        $reader = Auth::user();
        if($reader === NULL)
        {   
            $resMsg = ResponseMsg::NotLogin;
            $res = Response::GetResponseData($resType , $resMsg , $resData);
            return $res;
        }
        $count = intval($req->count);
        $offset = intval($req->offset);
        $text = $req->text;

        $posts = PostsModel::searchPost(explode(' ', $text),$count , $offset);
        $resData =  $posts->map(function($post) use ($reader) {
            return $post->jsonify($reader);
        });
           
        $res = Response::GetResponseData($resType , $resMsg , $resData);
        return $res;
    }
}

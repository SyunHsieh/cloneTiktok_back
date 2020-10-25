<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Posts as PostsModel;
use App\Models\User as UserModel;

use App\Responses\Response;
use App\Responses\ResponseType;
use App\Responses\ResponseMsg;

use DB;
use Auth;
use DateTime;
use Validator;

class LikesController extends Controller
{
    public static function SetLikesToPost(Request $req ,$postid ){
        $resType = ResponseType::SetUserLike;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;

        
        $user = Auth::user();
        $like = $req->isMethod('post') ? TRUE :FALSE;

        //not login 
        if($user === NULL){
            $resMsg = ResponseMsg::NotLogin;
            $res = Response::GetResponseData($resType , $resMsg , $resData);
            return $res;
        }

        //check target post exist.
        $post = PostsModel::getPost($postid);
        if($post ===NULL){
            $resMsg = ResponseMsg::TargetPostNotExist;
            $res = Response::GetResponseData($resType , $resMsg , $resData);
            return $res;
        }

        $userModel = UserModel::getUser($user->id);
        $userModel->setPostLike($post,$like);

        
        $resData = ['likeStatus'=>$like , 'postid'=>$post->id , 'likescount'=>$post->getLikesCount()];
        $res = Response::GetResponseData($resType , $resMsg , $resData);
            return $res;
    }
}

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

class CommentController extends Controller
{
    public static function SetCommentToPost(Request $req , $postid ){
        $resType = ResponseType::CreatePostComment;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;

        $validator = Validator::make($req->all(),[
            'comment'=>'required',
            ]);
        if($validator->fails()){
            $resMsg = ResponseMsg::RequestDataInvalid;
            return Response::GetResponseData($resType , $resMsg , $resData);
        }

        $user = Auth::user();
        $comment = $req->comment;
    
        //not login 
        if($user === NULL){
            $resMsg = ResponseMsg::NotLogin;
            return Response::GetResponseData($resType , $resMsg , $resData);
        }

        //check target post exist.
        $post = PostsModel::getPost($postid);
        if($post ===NULL){
            $resMsg = ResponseMsg::TargetPostNotExist;
            return Response::GetResponseData($resType , $resMsg , $resData);  
        }

        $userModel = UserModel::getUser($user->id);
       

        //Create comments
        $userModel->createComment($post , $comment);
        

        $resData = [ 'postid'=>$post->id , 'commentscount'=>$post->getCommentsCount()];
        $res = Response::GetResponseData($resType , $resMsg , $resData);
        return $res;
    }

    public function GetPostComments(Request $req , $postid){
        $resType = ResponseType::CreatePostComment;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;

        $validator = Validator::make($req->all(),[
            'count'=>'required',
            'offset'=>'required'
            ]);
        if($validator->fails()){
            $resMsg = ResponseMsg::RequestDataInvalid;
            return Response::GetResponseData($resType , $resMsg , $resData);
        }

        $user = Auth::user();
    
        //not login 
        if($user === NULL){
            $resMsg = ResponseMsg::NotLogin;
            return Response::GetResponseData($resType , $resMsg , $resData);
        }

        $count = $req->count;
        $offset = $req->offset;

        //check target post exist.
        $post = PostsModel::getPost($postid);
        if($post ===NULL){
            $resMsg = ResponseMsg::TargetPostNotExist;
            return Response::GetResponseData($resType , $resMsg , $resData);
        }

        $comments = $post->getComments($count , $offset);


        $resData = ['comments'=>$comments];
        return Response::GetResponseData($resType , $resMsg , $resData);
        
    }
}

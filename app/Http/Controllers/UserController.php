<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User as UserModel;
use DateTime;
use App\Responses\Response;
use App\Responses\ResponseType;
use App\Responses\ResponseMsg;
use App\Models\Posts as PostsModel;
use DB;
class UserController extends Controller
{
    //
    public function createAccount(Request $request){
        $resType = ResponseType::CreateAccount;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'password'=>'required',
            'account'=>'required',
            ]);

        if($validator->fails()){
            $resMsg = ResponseMsg::RequestDataInvalid;
            return Response::GetResponseData($resType , $resMsg , $resData);
        }

        // check account not exists;
        if(UserModel::isAccountExists($request['account'])){
            $resMsg = ResponseMsg::AccountIsExists;
            return Response::GetResponseData($resType , $resMsg , $resData);
        }


        $account = $request['account'];
        $salt = "testsalt";
        $passwordhash = bcrypt($request['password']);
        
        $name = $request['name'];
        
        $flag = UserModel::createUser($account , $passwordhash , $salt , $name );
        
        if(!$flag)
            $resMsg = ResponseMsg::CreateAccountFailed;
        else
            $reMsg = ResponseMsg::Successed;
        return Response::GetResponseData($resType , $resMsg , $resData);
    }

    public static function setUserLikesToPost($user , $postid , $like){
        $resType = ResponseType::SetUserLike;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;

        //reader not login 
        if($user === NULL){
            $resMsg = ResponseMsg::NotLogin;
            $res = Response::GetResponseData($resType , $resMsg , $resData);
            return $res;
        }

        //check target post exist.
        $post = PostsModel::where('id' , $postid)->first();
        if($post ===NULL){
            $resMsg = ResponseMsg::TargetPostNotExist;
            $res = Response::GetResponseData($resType , $resMsg , $resData);
            return $res;
        }

        $userModel = UserModel::getUser($user->id);

        $curLike = $userModel->likes()->where('postid',$postid)->first();

        $statistics = $post->postStatistics();

        //當 設定值與 實際值不同時才需要update DB.
        if($curLike===NULL && $like){
            $likes = $userModel->likes()->create(['postid'=>$post->id,'datetime'=>new DateTime()]);
            $statistics->update([
                'likescount'=>DB::raw('likescount+1'),
                ]);
        }
        elseif($curLike!==NULL && !$like){
            
            $curLike->delete();
            $statistics->update([
                'likescount'=>DB::raw('likescount-1'),
                ]);
        }


        $resMsg = ResponseMsg::Successed;
        $resData = ['likeStatus'=>$like , 'postid'=>$post->id , 'likescount'=>$statistics->first()->likescount];
        $res = Response::GetResponseData($resType , $resMsg , $resData);
            return $res;
        
    }
}

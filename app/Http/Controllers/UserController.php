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

class UserController extends Controller
{
    //
    public function CreateAccount(Request $request){
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
}

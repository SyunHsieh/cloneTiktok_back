<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Responses\Response;
use App\Responses\ResponseType;
use App\Responses\ResponseMsg;

class LoginController extends Controller
{
    //
    public function GetUserInfo(Request $req){

        $resType = ResponseType::GetUserInfo;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;

        if($req->session()->get('userid') === NULL)
            $resMsg = ResponseMsg::NotLogin;
        else{
            $resData = Auth::user();
        }
            

        $res = Response::GetResponseData($resType , $resMsg , $resData);
        return $res;
        
    }
    public function Login(Request $req){
        $resType = ResponseType::Login;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;

        if(Auth::attempt(['account'=> $req->account,'password' => $req->password]))
        {   
            //  Store user id in session.
            $req->session()->put('userid',Auth::id());
            $resData = Auth::user();
        }
        else
        {
            $resMsg = ResponseMsg::LoginFailed;
        }
        $res = Response::GetResponseData($resType , $resMsg , $resData);
        return $res;
    }
    public function Logout(Request $req){
        $resType = ResponseType::Logout;
        $resMsg = ResponseMsg::Successed;
        $resData = NULL;

        //Clear session;
        Auth::logout();
        $req->session()->flush();
        
        $res = Response::GetResponseData($resType , $resMsg , $resData);
        return $res;
        
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User as UserModel;


class UserController extends Controller
{
    //
    public function createAccount(Request $request){
        
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'password'=>'required',
            'account'=>'required',
            ]);

        if($validator->fails()){
            return response()->json(['error_code' => '404'], 401);
        }

        // check account not exists;
        if(UserModel::isAccountExists($request['account']))
            return 'account was exists.';

        $account = $request['account'];
        $salt = "testsalt";
        $passwordhash = bcrypt($request['password']);
        
        $name = $request['name'];
        
        $flag = UserModel::createUser($account , $passwordhash , $salt , $name );
        
        return $flag;
    }

    
}

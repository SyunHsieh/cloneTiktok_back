<?php

namespace App\Responses;


class ResponseMsg {
    const Successed = 'Successed';
    const RequestDataInvalid = 'Request data is invalied.';

    const AccountIsExists = 'Account exists.';
    
    const LoginFailed = 'Login failed.';
    const NotLogin = 'Not login.';
    // const LoginSuccessed = 'Login Successed.';
    // const LogoutSuccseesd = 'Logout successed.';

    // const CreateAccountSuccsessed = 'Create account successed.';
    const CreateAccountFailed = 'Create account failed.';
    const AccountExists = 'The user account is exists.';

    const CreatePostFailed = 'Create post failed';
    
    const GetUserPostsFailed = 'Get user posts failed.';
    const GetPostFailed = 'Get posts failed';

    const SetUserLikeFailed = 'Set user like failed.';
    const TargetPostNotExist = 'The post is not exist.';
   
    const SearchPostFailed ='Searching post text failed.abnf';
    const SearchUserFailed = 'Searching user name failed.';
}


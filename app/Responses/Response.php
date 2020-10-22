<?php

namespace App\Responses;

use App\Responses\ResponseMsg;
use App\Responses\ResponseType;


class Response {


    public static function GetResponseData( $type ,  $msg ,$data)
    {   
        if($data === NULL)
            $retData = ['type'=>$type,'msg'=>$msg];
        else
            $retData = ['type'=>$type,'msg'=>$msg,'data'=>$data];
        return $retData;
    }
}

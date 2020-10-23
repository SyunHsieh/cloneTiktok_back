<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GCS as GCSModel;


class GCSController extends Controller
{   
    static function genRandomStr($length = 10 , $chars='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        $charsLength = strlen($chars);
        $randomStr = '';
        for ($i = 0; $i < $length; $i++) {
            $randomStr .= $chars[rand(0, $charsLength - 1)];
        }
        return $randomStr;
    }

    public static function UploadObject($bucketname , $blobname , $source,$fileExtension , $setPublic,$isRandomBlobName = FALSE){
        $file = fopen($source , 'r');
        $contentType = GCSController::_getFileContentType($fileExtension);
        
        while(GCSModel::IsBlobExists($bucketname, $blobname)){
            if($isRandomBlobName)
                $blobname = GCSController::genRandomStr();
            else
                return ['flag'=>FALSE];
        }
        
        
        return GCSModel::UploadObject($bucketname , $blobname,$file,$contentType,$setPublic);

    }
    
    public static function DeleteObject($bucketname , $blobname ){
        return GCSModel::DeleteObject($bucketname , $blobname);
    }
    private static function _getFileContentType($fileExtension){
        switch($fileExtension){
            case 'mp4':
                return 'video/mp4';
                break;
            case 'jpg':
            case 'jpeg':
                return 'image/jpeg';
                break;
            return NULL;
        }
    }
}

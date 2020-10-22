<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GCS as GCSModel;


class GCSController extends Controller
{
    public static function UploadObject($bucketname , $blobname , $source,$fileExtension , $setPublic){
        $file = fopen($source , 'r');
        $contentType = GCSController::_getFileContentType($fileExtension);

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

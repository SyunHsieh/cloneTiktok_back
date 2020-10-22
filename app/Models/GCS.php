<?php

namespace App\Models;

use Google\Cloud\Storage\StorageClient;
use google\appengine\api\cloud_storage\CloudStorageTools;
class GCS 
{
    private static function _getStorageClient(){
        $storage = new StorageClient([
            'keyFilePath'=>realpath('./../gcpkey.json')
        ]);

        return $storage;
    }


   public static function UploadObject($bucketname , $blobname , $file , $contentType=NULL , $setPublic = FALSE){
       ///return json => {
       ///  uri,
       ///  flag   
       ///}
        $retUri = NULL;
        $retFlag = False;
        
        $storage = GCS::_getStorageClient();
        
        
        $bucket = $storage->bucket($bucketname);
        // if(!$bucket->exists())
        //     return ['uri'=>$retUri , 'flag'=>$retFlag];
        
        $object = $bucket->upload($file , [
            'name' => $blobname,
            'metadata' => ['contentType'=>$contentType]
        ]);
        
        $isUploadSuccessed = $object->exists();
        
        
        if($isUploadSuccessed){
            if($setPublic )
                $object->update(['acl' => []], ['predefinedAcl' => 'PUBLICREAD']);

            $retFlag = TRUE;
            $retUri = $object->gcsUri();
            
        }
        
        // return $object->signedUrl(new \DateTime('tomorrow'));;
        return ['uri'=>$retUri , 'flag'=>$retFlag];
   }

   public static function DeleteObject($bucketname , $blobname){
       /// return TRUE if blob not exists.
       /// return FALSE when bucket not exist or blob delete failed.
       
        $storage = GCS::_getStorageClient();
    
    
        $bucket = $storage->bucket($bucketname);
        // if(!$bucket->exists())
        //     return FALSE;
        
        $object = $bucket->object($blobname);
        
        if(!$object->exists())
            return TRUE;
        
        $object->delete();

        //Check blob not exists.
        if(!$object->exists())
            return TRUE;

        return FALSE;
        

   }

   public static function IsBlobExists($bucketname , $blobname ){
        $storage = GCS::getStorageClient();
        $bucket = $storage->bucket($bucketname);

        $object = $bucket->object($blobname);

        return $object->exists();
   }
}

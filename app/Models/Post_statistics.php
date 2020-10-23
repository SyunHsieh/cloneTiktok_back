<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post_statistics extends Model
{
    use HasFactory;
    protected $table = "post_statistics"; 
    public $timestamps = false;
    protected $fillable = [
        'postid','likescount','commentscount'
    ];

    public static function createPostStatistic($postid){
        $postStatistics = Post_statistics::create([
            'postid' => $postid,
            'likescount' => 0,
            'commentscount' => 0
        ]);
            
        return $postStatistics ? TRUE:FALSE;
    }
}

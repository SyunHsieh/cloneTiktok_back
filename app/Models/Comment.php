<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
   
    use HasFactory;
    protected $table = "comment"; 
    public $timestamps = false;
    protected $fillable = [
        'userid' , 'postid' , 'text' , 'datetime'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    use HasFactory;
    protected $table = "following"; 
    public $timestamps = false;
    protected $fillable = [
        'userid' , 'targetuserid','datetime'
    ];
}

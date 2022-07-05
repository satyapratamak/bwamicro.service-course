<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lessons extends Model
{
    use HasFactory;

    protected $table = 't_lessons';
    protected $fillable = [
        'name', 't_chapters_id', 'video_url'
    ];
}

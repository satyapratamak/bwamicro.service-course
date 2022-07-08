<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageCourse extends Model
{
    use HasFactory;

    protected $table = 't_image_courses';
    protected $fillable = [
        'image_url', 't_courses_id',
    ];
}

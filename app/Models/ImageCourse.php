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

    protected $date = ['updated_at', 'created_at'];
    public function getCreatedAtAttribute($date)
    {
        //return  $date->format('Y-m-d H:i');
        return date('Y-m-d H:i:s', strtotime($date));
    }

    public function getUpdatedAtAttribute($date)
    {
        //return $date->format('Y-m-d H:i');
        return date('Y-m-d H:i:s', strtotime($date));
    }
}

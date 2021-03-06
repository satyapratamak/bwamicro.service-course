<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    protected $table = 't_courses';
    protected $fillable = [
        'name', 'is_certificate', 'thumbnail', 'type',
        'status', 'price', 'level', 'description', 't_mentors_id',
    ];

    public function mentor()
    {
        return $this->belongsTo('App\Models\Mentors');
    }

    public function chapters()
    {
        return $this->hasMany('App\Models\Chapters')->orderBy('id', 'ASC');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ImageCourses')->orderBy('id', 'DESC');
    }
}

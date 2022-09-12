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

    protected $cast = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function mentor()
    {
        return $this->belongsTo('App\Models\Mentor');
    }

    public function chapters()
    {
        return $this->hasMany('App\Models\Chapters', 't_courses_id')->orderBy('id', 'ASC');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ImageCourse', 't_courses_id')->orderBy('id', 'DESC');
    }
}

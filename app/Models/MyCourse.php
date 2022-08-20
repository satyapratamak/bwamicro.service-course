<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyCourse extends Model
{
    use HasFactory;
    protected $table = 't_my_courses';
    protected $fillable = [
        't_courses_id', 'user_id',
    ];

    public function courses()
    {
        return $this->belongsTo('App\Models\Course');
    }

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

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
}

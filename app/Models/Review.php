<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $table = 't_reviews';
    protected $fillable = [
        't_courses_id', 'user_id', 'ratings', 'note',
    ];

    public function courses()
    {
        return $this->belongsTo('App\Models\Course');
    }
}

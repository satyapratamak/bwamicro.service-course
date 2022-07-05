<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapters extends Model
{
    use HasFactory;

    protected $table = 't_chapters';
    protected $fillable = [
        'name', 't_courses_id',
    ];

    public function lessons()
    {
        return $this->hasMany('App\Models\Lessons')->orderBy('id', 'ASC');
    }
}

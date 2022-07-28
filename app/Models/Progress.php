<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'read_chapter',
        'length_chapter',
    ];

    public function lesson()
    {
        return $this->hasOne(Lesson::class);
    }
}

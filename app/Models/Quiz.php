<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'pelajaran',
        'question_text',
        'answer_options'
    ];

    public function lesson()
    {
        return $this->belongTo(Lesson::class);
    }

    // public function answerOption()
    // {
    //     return $this->hasMany(AnswerOption::class);
    // }
}

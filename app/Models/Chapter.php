<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'pelajaran',
        'judul_bab',
        'materi'
      ];
    
      public function lesson() {
        return $this->belongsTo(Lesson::class);
      }
}

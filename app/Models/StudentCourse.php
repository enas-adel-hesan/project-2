<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    use HasFactory;
    protected $fillable=['student_id','course_id','subsicribtion_date'];
    public function course(){

return $this->belongsTo(Course::class);

    }
    public function student(){

return $this->belongsTo(Student::class);

    }
}

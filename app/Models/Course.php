<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable=['category_id','teacher_id','name','price','discription','accepted','rate'];
//return the category this course belongs to
    public function category(){
        return $this->belongsTo(Category::class);
    }
    //return the couponse for this course
public function coupones(){
    return $this->hasMany(Coupon::class);
}

public function students(){
    return $this->hasMany(StudentCourse::class);
}


public function videos(){
    return $this->hasMany(Video::class);
}
public function teacher()
{
    return $this->belongsTo(Teacher::class);
}


}

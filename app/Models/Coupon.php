<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable=['course_id','name','count','value','expire_date'];
    public function course(){
return $this->belongsTo(Course::class);




    }
}


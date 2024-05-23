<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable=['name','image'];


    
    //return the courses of this category
    public function courses(){
        return $this->hasMany(Course::class);
    }
}

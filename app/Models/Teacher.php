<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password','specialization' ,'full_name','previous_place_of_work','years_of_experience'// Add other fields as necessary
    ];

    // Method to handle file upload
    public function setImageAttribute($value)
    {
        if ($value) {
            $this->attributes['image'] = $value->store('images', 'public');
        }
    }
//return the courses of this teacher
    public function courses(){
        return $this->hasMany(Course::class);
    }

//return the wallet of the teacher 
    public function teacher_wallets(){
        return $this->hasOne(teacher_wallets::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($teacher) {
            $teacher->full_name = $teacher->first_name . ' ' . $teacher->last_name;
        });

        static::updating(function ($teacher) {
            $teacher->full_name = $teacher->first_name . ' ' . $teacher->last_name;
        });
    }
}

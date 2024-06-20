<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class teacher_wallets extends Model
{
    use HasFactory;
    protected $fillable=['teacher_id','value'];
    public function teacher(){

return $this->belongsTo(Teacher::class);



    }
}

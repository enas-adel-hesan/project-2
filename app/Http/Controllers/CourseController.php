<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function addCourse(Request $request){
$teacher_id=auth('teacher')->user()->id;
$validatedData = $request->validate([
    'category_id' => 'required|exists:categories,id',
    'name' => 'required|string|max:255',
    'price' => 'required|numeric',
    'discription' => 'required|string|max:1000' ,//image should be added later

]);
$validatedData['teacher_id']=$teacher_id;
$course=Course::create($validatedData);

return response()->json(['status'=>'success','teacher'=>$course]);





    }
}

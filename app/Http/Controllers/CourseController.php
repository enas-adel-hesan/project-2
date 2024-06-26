<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function addCourse(Request $request)
    {
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
    public function searchCoursesbyCategoryId( $categoryId)
    { 
        $courses = Course::where('category_id', $categoryId)->get();
        $courseNames = $courses->pluck('name');
    
        return response()->json(['course_names' => $courseNames]);// Get the category ID
       
      
    } 


    public function getInfoCoursebyId($courseId)
    {
        $course = Course::with('teacher', 'category')->find($courseId);
    
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }
    
        $formattedCourse = [
            'teacher_name' => $course->teacher->full_name,
            'course_name' => $course->name,
            'description' => $course->discription,
            'price' => $course->price,
            'category' =>  $course->category->name,
        ];
    
        return response()->json(['course' => $formattedCourse]);
    }
    
        public function getAllCourses()
        {  $courses =  Course::select('name')->get();
            return response()->json(['courses' => $courses], 200);
           
        }
    }

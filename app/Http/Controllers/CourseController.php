<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Category;
use DB;

class CourseController extends Controller
{
    public function page(Request $r)
    {
        $length = $r->get('length',10);
        $start = $r->get('start',0);
        $search = $r->get('search');
    
        // التحقق من أن $length و $start أعداد صحيحة وليست فارغة
        if (!is_numeric($length) || !is_numeric($start)) {
            return response()->json(['error' => 'Invalid length or start parameters'], 400);
        }
    
        $query = Course::select('*');
    
        // التحقق من وجود قيمة في $search ووجود المفتاح 'value'
        if (!empty($search) && isset($search['value']) && !empty($search['value'])) {
            $query->where('name', 'like', '%' . $search['value'] . '%');
        }
    
        $data = $query->skip($start)
                      ->take($length)
                      ->get();
    
        $arr = array();
        foreach ($data as $d) {
            $category = Category::find($d->category_id)->name;
           $teacher=Teacher::find($d->teacher_id)->first_name;
            $arr[] = array(
                'name' => $d->name,
                'category' => $category,
                'teacher'=>$teacher,
                'price'=>$d->price,
                'discription'=>$d->discription
                
                
            );
        }
    
        $total_members = Course::count();
    
        // التحقق من وجود قيمة في $search ووجود المفتاح 'value' قبل حساب $count
        if (!empty($search) && isset($search['value']) && !empty($search['value'])) {
            $count = DB::select("select * from courses where name like '%" . $search['value'] . "%'");
        } else {
            $count = Course::all();
        }
        $recordsFiltered = count($count);
    
        $data = array(
            'recordsTotal' => $total_members,
            'recordsFiltered' => $recordsFiltered,
            'data' => $arr,
        );
    
        return response()->json($data);
    }
	
    public function index()
    {
		
	
		return view('courses.index');
        //
    }



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
    

}

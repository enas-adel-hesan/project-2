<?php

namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\Course;
use Illuminate\Http\Request;

class StudentCourseController extends Controller
{
    
        public function subscribeToCourse(Request $request, $studentId, $courseId)
        {
            // Find the student by ID
            $student = Student::findOrFail($studentId);
    
            // Find the course by ID
            $course = Course::findOrFail($courseId);
    
            // Get the course price
            $coursePrice = $course->price;
    
            // Get the student's wallet
            $studentWallet = $student->student_wallets()->first();
            if (!$studentWallet) {
                return response()->json(['error' => 'Student wallet not found'], 404);
            }
    
            // Check if the wallet balance is sufficient for the subscription
            if ($studentWallet->value < $coursePrice) {
                return response()->json(['error' => 'Insufficient funds'], 400);
            }
    
            // Withdraw from student's wallet
            $studentWallet->value -= $coursePrice;
            $studentWallet->save();
    
            // Deposit into teacher's wallet (assuming you have a teacher relationship in your Course model)
            $teacherWallet = $course->teacher->teacher_wallets()->first();
            if (!$teacherWallet) {
                return response()->json(['error' => 'Teacher wallet not found'], 400);
            }
            $teacherWallet->value += $coursePrice;
            $teacherWallet->save();
    
            // Create a subscription record (StudentCourse)
            $subscription = StudentCourse::create([
                'student_id' => $studentId,
                'course_id' => $courseId,
                'subsicribtion_date' => now(),
            ]);
    
            return response()->json(['message' => 'Subscription successful', 'subscription' => $subscription], 200);
        }
    
}    



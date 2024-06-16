<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class StudentAuthController extends Controller
{

    public function register(Request $request)
    {

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'password' => 'required|string|min:6' //image should be added later
        ]);

        $validatedData['password'] = Hash::make($request->password);

        $student = Student::create($validatedData);

        return response()->json(['student' => $student], 200);
    }


    public function login(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $student = Student::where('email', $request->email)->first();
     
        if (!$student || !Hash::check($request->password, $student->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $student->createToken('student-token')->plainTextToken;

        return response()->json(['token' => $token], 200);;
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $id,
            // Exclude password validation and hashing
        ]);

        // Update the student with the validated data, excluding the password
        $student->update($validatedData);

        return response()->json(['message' => 'Student account updated successfully!', 'student' => $student], 200);
    }

}

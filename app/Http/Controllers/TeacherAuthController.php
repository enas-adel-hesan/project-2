<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TeacherAuthController extends Controller
{
    public function register(Request $request)
    {

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers',
            'password' => 'required|string|min:6' ,//image should be added later
            'specialization'=>'required|string|max:255'
        ]);

        $validatedData['password'] = Hash::make($request->password);

        $teacher = Teacher::create($validatedData);
        $token = $teacher->createToken('teacher-token')->plainTextToken;

        return response()->json(['teacher' => $teacher,'token'=>$token], 200);
    }


    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $teacher = Teacher::where('email', $request->email)->first();

        if (!$teacher || !Hash::check($request->password, $teacher->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $teacher->createToken('teacher-token')->plainTextToken;

        return response()->json(['token' => $token], 200);;
    }


    public function update(Request $request, $id)
    {
        $teacher = teacher::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers,email,' . $id,
             'specialization'=>'required|string|max:255'
            // Exclude password validation and hashing
        ]);

        // Update the student with the validated data, excluding the password
        $teacher->update($validatedData);

        return response()->json(['message' => 'teacher account updated successfully!', 'student' => $teacher], 200);
    }

}



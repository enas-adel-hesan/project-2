<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\student_wallets;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class StudentAuthController extends Controller
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
    
        $query = Student::select('*');
    
        // التحقق من وجود قيمة في $search ووجود المفتاح 'value'
        if (!empty($search) && isset($search['value']) && !empty($search['value'])) {
            $query->where('first_name', 'like', '%' . $search['value'] . '%');
        }
    
        $data = $query->skip($start)
                      ->take($length)
                      ->get();
    
        $arr = array();
        foreach ($data as $d) {
            $arr[] = array(
                'first_name' => $d->first_name,
                'last_name' => $d->last_name,
                'email' => $d->email,
                
            );
        }
    
        $total_members = Student::count();
    
        // التحقق من وجود قيمة في $search ووجود المفتاح 'value' قبل حساب $count
        if (!empty($search) && isset($search['value']) && !empty($search['value'])) {
            $count = DB::select("select * from students where first_name like '%" . $search['value'] . "%'");
        } else {
            $count = Student::all();
        }
        $recordsFiltered = count($count);
    
        $data = array(
            'recordsTotal' => $total_members,
            'recordsFiltered' => $recordsFiltered,
            'data' => $arr,
        );
    
        return response()->json($data);
    }
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
        $StudentWallet = new student_wallets(); // Assuming you have a Wallet model
        $StudentWallet->value = 0; // Initialize the wallet amount (optional)
        $student->student_wallets()->save($StudentWallet);
  
    
    
        // Return the student and wallet information
        return response()->json(['student' => $student, 'StudentWallet' => $StudentWallet], 200);
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


   
     
    public function index()
    {
	
       
		return view('student.index');
        //
    }


    public function addMoneyToWallet(Request $request, $id)
    {
        // Find the student by ID
        $student = Student::findOrFail($id);
    
        // Validate the request data (you can adjust the validation rules as needed)
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0', // Assuming you're sending the 'amount' field
        ]);
    
        // Get the student's wallet or create one if it doesn't exist
        $studentWallet = $student->student_wallets ?? new student_wallets();
        $studentWallet->value += $validatedData['amount']; // Deposit the specified amount
        $studentWallet->save();
    
        return $studentWallet->value; // Return the updated wallet balance
    }
    
}

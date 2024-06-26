<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\teacher_wallets;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TeacherAuthController extends Controller
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

    $query = Teacher::select('*');

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
            'specialization' => $d->specialization,
            'previous_place_of_work' => $d->previous_place_of_work,
            'years_of_experience' => $d->years_of_experience,
        );
    }

    $total_members = Teacher::count();

    // التحقق من وجود قيمة في $search ووجود المفتاح 'value' قبل حساب $count
    if (!empty($search) && isset($search['value']) && !empty($search['value'])) {
        $count = DB::select("select * from teachers where first_name like '%" . $search['value'] . "%'");
    } else {
        $count = Teacher::all();
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
            'email' => 'required|string|email|max:255|unique:teachers',
            'password' => 'required|string|min:6' ,//image should be added later
            'specialization'=>'required|string|max:255',
            'years_of_experience'=>'required|int',
            'previous_place_of_work'=>'required|string|max:255'

        ]);

        $validatedData['password'] = Hash::make($request->password);

        $teacher = Teacher::create($validatedData);
        $token = $teacher->createToken('teacher-token')->plainTextToken;
        $teacherWallet = new teacher_wallets(); // Assuming you have a Wallet model
        $teacherWallet ->value = 0; // Initialize the wallet amount (optional)
        $teacher->teacher_wallets()->save($teacherWallet);
        return response()->json(['teacher' => $teacher,'teacherWallet' => $teacherWallet,'token'=>$token], 200);
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

        return response()->json(['message' => 'teacher account updated successfully!', 'teacher' => $teacher], 200);
    }

    
    public function index()
    {
	
       
		return view('teacher.index');
        //
    }
    public function addMoneyToWallet(Request $request, $id)
    {
        // Find the student by ID
        $teacher = Teacher::findOrFail($id);
    
        // Validate the request data (you can adjust the validation rules as needed)
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0', // Assuming you're sending the 'amount' field
        ]);
    
        // Get the student's wallet or create one if it doesn't exist
        $studentWallet = $teacher->teacher_wallets ?? new teacher_wallets();
        $studentWallet->value += $validatedData['amount']; // Deposit the specified amount
        $studentWallet->save();
    
        return $studentWallet->value; // Return the updated wallet balance
    }

        public function searchTeacherByFullName(Request $request)
    {
        $fullName = $request->input('full_name');

        $teachers = Teacher::query()
            ->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$fullName%"])
            ->select('full_name', 'specialization', 'email') // Select only the desired columns
            ->get();

        return response()->json(['teachers' => $teachers], 200);
    }


    public function getAllTeacherFullNames()
    {
        $teachers = Teacher::select('full_name')->get();

        return response()->json(['teachers' => $teachers], 200);
    }


    public function getInformationTeacherById($teacherId)
    {
        $teacher = Teacher::with('courses')->find($teacherId);
      
        if (!$teacher) {
            return response()->json(['error' => 'Teacher not found'], 404);
        }
        $name = $teacher->courses->pluck('name');
        $info = [
            'full_name' => $teacher->full_name,
            'specialization' => $teacher->specialization,
            'email' => $teacher->email,
            'previous_place_of_work' => $teacher->previous_place_of_work,
            'years_of_experience' => $teacher->years_of_experience,
           'course_names' => $name, 
        ];
    
        return response()->json(['teacher_info' => $info], 200);
    }
}



<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
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
    
    public function index()
    {
	
       
		return view('teacher.index');
        //
    }
    
}



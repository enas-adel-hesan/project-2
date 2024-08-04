<?php

namespace App\Http\Controllers;

use App\Models\user;
use App\Models\role;
use Illuminate\Http\Request;
use DB;
use File;
use Hash;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function page(Request $r)
    {
        $length = $r->get('length',10);
        $start = $r->get('start',0);
        $search = $r->get('search');
    
        // التحقق من أن $length و $start أعداد صحيحة وليست فارغة
        if (!is_numeric($length) || !is_numeric($start)) {
            return response()->json(['error' => 'Invalid length or start parameters'], 400);
        }
    
        $query = User::select('*');
    
        // التحقق من وجود قيمة في $search ووجود المفتاح 'value'
        if (!empty($search) && isset($search['value']) && !empty($search['value'])) {
            $query->where('username', 'like', '%' . $search['value'] . '%');
        }
    
        $data = $query->skip($start)
                      ->take($length)
                      ->get();
    
        $arr = array();
        foreach ($data as $d) {
            $role = role::find($d->role_id);
            if ($role) {
                $type = $role->name;
            } else {
                $type = 'Role not found'; // أو يمكنك تركها فارغة أو تعيين قيمة افتراضية أخرى
            }
            $arr[] = array(
                'username' => $d->username,
                'password' => $d->password,
                'role' => $type,
                'action'=>"<a href='user/".$d->id."/edit' class='btn btn-success'><i class='fas fa-edit'></i> Edit</a>
               
                <a href='user/".$d->id."/deleted'  class='btn btn-danger'><i class='fas fa-trash'></i> Delete </a>
           
                 
                "
                
            );
        }
    
        $total_members = User::count();
    
        // التحقق من وجود قيمة في $search ووجود المفتاح 'value' قبل حساب $count
        if (!empty($search) && isset($search['value']) && !empty($search['value'])) {
            $count = DB::select("select * from users where username like '%" . $search['value'] . "%'");
        } else {
            $count = User::all();
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
		
	
		return view('user.index');
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$user=role::all();
		return view("user.create",["roleIds"=>$user]);
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$this->validate($request,[
		
		'username'=>'required|unique:users',
		'password'=>'required',
	
		]);
		
		
		
		
		$user=new user();
		$user->username=$request->username;
		$user->password=Hash::make($request->password);
		$user->role_id=$request->role_id;
         $user->save();		

		return redirect()->route("user.index");
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function show(user $user)
    {
        //
		return view("user.show",["user"=>$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(user $user)
    {
			$type=role::all();
		return view("user.edit",["roleIds"=>$type,"user"=>$user]);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, user $user)
    {
			$user->username=$request->username;
		$user->password=$request->password;
	
		
         $user->save();	
		 	return redirect()->route("user.index");
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(user $user)
    {
		DD("IA M HERE");
        //
    }
	   public function deleted($id)
    {
		$user=user::find($id);
		$user->delete();
		return redirect()->route("user.index");
		
        //
    }
}

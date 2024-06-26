<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Course;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use DB;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function page(Request $r)
    {
        $length = $r->get('length', 10);
        $start = $r->get('start', 0);
        $search = $r->get('search');
    
        if (!is_numeric($length) || !is_numeric($start)) {
            return response()->json(['error' => 'Invalid length or start parameters'], 400);
        }
    
        $query = Coupon::query();
    
        if (!empty($search) && isset($search['value']) && !empty($search['value'])) {
            $query->where('name', 'like', '%' . $search['value'] . '%');
        }
    
        $data = $query->skip($start)
                      ->take($length)
                      ->get();
    
        $arr = array();
        foreach ($data as $d) {
            $course = Course::find($d->course_id)->name; // تصحيح اسم المتغير هنا
            $arr[] = array(
                'name' => $d->name,
                'course' => $course, // استخدام المتغير الصحيح هنا
                'count' => $d->count,
                'value' => $d->value,
                'action' => "
                    <a href='coupon/{$d->id}/deleted' class='btn btn-danger'><i class='fas fa-trash'></i> Delete</a>
                "
            );
        }
    
        $total_members = Coupon::count();
    
        if (!empty($search) && isset($search['value']) && !empty($search['value'])) {
            $recordsFiltered = Coupon::where('name', 'like', '%' . $search['value'] . '%')->count();
        } else {
            $recordsFiltered = $total_members;
        }
    
        $data = array(
            'recordsTotal' => $total_members,
            'recordsFiltered' => $recordsFiltered,
            'data' => $arr,
        );
    
        return response()->json($data);
    }
    

    
	
    public function index()
    {
		
	
		return view('coupon.index');
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$coupon=Course::all();
		return view("coupon.create",["courseIds"=>$coupon]);
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
    $this->validate($request, [
        'name' => 'required',
        'value' => 'required|numeric',
        'count' => 'required|integer',
        'course_id' => 'required|exists:courses,id'
    ]);

    $coupon = new Coupon();
    $coupon->name = $request->name;
    $coupon->course_id = $request->course_id;
    $coupon->count = $request->count;
    $coupon->value = $request->value;
    $coupon->save();

    return redirect()->route("coupon.index");
}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(role $role)
    {
        //
		return view("role.show",["role"=>$role]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
{
    $role = Role::find($id);
    if (!$role) {
        // يمكن توجيه المستخدم إلى صفحة خطأ هنا
        return redirect()->route("role.index")->with('error', 'Role not found.');
    }
    return view("role.edit", ["role" => $role]);
}


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $role->name = $request->name;
    $role->save();

    return redirect()->route("role.index")->with('success', 'Role updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\role  $role
     * @return \Illuminate\Http\Response
     */
   
	   public function deleted($id)
    {
		$coupon=Coupon::find($id);
		$coupon->delete();
		return redirect()->route("coupon.index");
		
        //
    }
}

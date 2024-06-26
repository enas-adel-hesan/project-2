<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use DB;

class CategoryController extends Controller
{
   


    public function index()
    {
	
       
		return view('category.index');
        //
    }    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function page(Request $r)
    {
        $length = $r->get('length', 10);
        $start = $r->get('start', 0);
        $search = $r->get('search');

        // التحقق من أن $length و $start أعداد صحيحة وليست فارغة
        if (!is_numeric($length) || !is_numeric($start)) {
            return response()->json(['error' => 'Invalid length or start parameters'], 400);
        }

        $query = Category::query();

        // التحقق من وجود قيمة في $search ووجود المفتاح 'value'
        if (!empty($search) && isset($search['value']) && !empty($search['value'])) {
            $query->where('name', 'like', '%' . $search['value'] . '%');
        }

        $data = $query->skip($start)
                      ->take($length)
                      ->get();

        $arr = array();
        foreach ($data as $d) {
            $arr[] = array(
                'name' => $d->name,
                'action' => "
                   
                    <a href='category/{$d->id}/deleted' class='btn btn-danger'><i class='fas fa-trash'></i> Delete</a>
                "
            );
        }

        $total_members = Category::count();

        // التحقق من وجود قيمة في $search ووجود المفتاح 'value' قبل حساب $count
        if (!empty($search) && isset($search['value']) && !empty($search['value'])) {
            $recordsFiltered = Category::where('name', 'like', '%' . $search['value'] . '%')->count();
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
	


    
	
   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		
		return view("category.create");
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
		
		'name'=>'required',
	
		]);
		
		
				
		$category=new Category();
		$category->name=$request->name;

		
         $category->save();		

		return redirect()->route("category.index");
        //
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
		$category=category::find($id);
		$category->delete();
		return redirect()->route("category.index");
		
        //
    }
}

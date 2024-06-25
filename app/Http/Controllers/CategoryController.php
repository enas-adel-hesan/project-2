<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use DB;

class CategoryController extends Controller
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
    
        $query = Category::select('*');
    
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
                
            );
        }
    
        $total_members = Category::count();
    
        // التحقق من وجود قيمة في $search ووجود المفتاح 'value' قبل حساب $count
        if (!empty($search) && isset($search['value']) && !empty($search['value'])) {
            $count = DB::select("select * from categories where name like '%" . $search['value'] . "%'");
        } else {
            $count = Category::all();
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
	
       
		return view('category.index');
        //
    }
}

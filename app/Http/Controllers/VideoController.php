<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
class VideoController extends Controller
{
    public function uploadVideo(Request $request){
$validatedData=$request->validate([

'course_id'=>'required|numeric|exists:courses,id',
'title'=>'required|string',
'discription'=>'required|string',
'file' => 'required|mimes:mp4,avi',
'thumbnail'=>'required|mimes:jpeg,png,jpg,heic'



]);



$file = $request->file('file');
$fileName = time() . '.' . $file->getClientOriginalExtension();
$course_id=$validatedData['course_id'];
$file->move(public_path("uploads/courses/$course_id/videos"), $fileName);
$thumbnail = $request->file('thumbnail');
$thumbnailName = time() . '.' . $thumbnail->getClientOriginalExtension();
$thumbnail->move(public_path("uploads/courses/$course_id/thumbnails"), $thumbnailName);

$video=Video::create($validatedData);
$video['file']=$fileName;
$video['thumbnail']=$thumbnailName;

return response()->json(['status'=>'success','data'=>$video]);

    }
}

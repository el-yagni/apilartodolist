<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Auth;
use Illuminate\Http\Request;
use Storage;
use Validator;

class VideoController extends Controller
{
    public function index()
    {
        $video = Video::all();

        if (!$video) {
            return response()->json([
                "status" => false,
                "message" => "No Videos Here",
            ], 404);
        }
        return response()->json([
            "status" => true,
            "message" => "All Videtos",
            "data" => $video
        ], 200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "description" => "required",
            "file" => "mimetypes:video/avi,video/mp4,video/mpeg",
            "user_id" => "required",
            "user_name" => "required"
        ]);


        if ($request->file) {
            $fileName = hash("sha256", "abcdefghijklmnopqrstuvwxyz");
            $extensionz = $request->file->extension();
            $request['url'] = Storage::path('./public/storage/'.".".$fileName.".".$extensionz) ;
            Storage::putFileAs('public', $request->file, $fileName.".".$extensionz);
        }
        
        
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->failed(),
                "data" => $validator->errors()
            ], 301);
        }

        

        Video::create($request->all());
        return response()->json([
            "status" => true,
            "message" => "Success add Videos",
        ]);

    }


    public function delete(Request $request, $id)
    {
        $data = Video::find($id);
        if (!$data) {
            return response()->json([
                "status" => false,
                "message" => "Data not Found"
            ], 404);
        } else {
            $data->delete();
            return response()->json([
                "status" => true,
                "message" => "Data successfully deleted"
            ]);
        }
    }
}

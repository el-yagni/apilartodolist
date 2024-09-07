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
        $data = new Video();
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "description" => "required",
            "file" => "mimetypes:video/avi,video/mp4,video/mpeg",
            "user_id" => "required",
            "user_name" => "required"
        ]);

        $data->title = $request->title;
        $data->description = $request->description;
        $fileName = hash("sha256", "abcdefghijklmnopqrstuvwxyz");
        $extensionz = $request->file->extension();
        
        $file = $request->file('file');
        $data->user_id = $request->user_id;
        $data->user_name = $request->user_name;
        if (Storage::disk('public')->put('main/' . "video", $file)) {
            $data->url = 'https://apilartodolist.vercel.app/storage/'.$fileName.".".$extensionz;
            $data->save();
            return response()->json([
                "status" => true,
                "message" => "Success add Videos",
            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->failed(),
            ], 301);
        }
        

        

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

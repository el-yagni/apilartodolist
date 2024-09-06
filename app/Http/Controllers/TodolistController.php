<?php

namespace App\Http\Controllers;

use App\Models\todolist;
use Illuminate\Http\Request;
use Validator;

class TodolistController extends Controller
{
    public function index()
    {
        $data = todolist::orderBy("id", "desc")->get();

        if (!$data) {
            return response()->json([
                "status" => 404,
                "message" => "Nothing Data Here!",

            ]);
        } else {
            return response()->json([
                "status" => 200,
                "data" => $data
            ]);
        }
    }

    public function findList($id)
    {
        $data = todolist::find($id);
        if (!$data) {
            return response()->json([
                "status" => 404,
                "message" => "Data Not Found"
            ]);
        } else {
            return response()->json([
                "status" => 200,
                "data" => $data
            ]);
        }
    }


    public function Update(Request $request, $id)
    {
        $data = todolist::find($id);

        if (empty($data)) {
            return response()->json([
                "status" => false,
                "message" => "Data not Found"
            ], 404);
        } else {
            $data->update($request->all());
            return response()->json([
                "status" => 200,
                "message" => "Successfully Update Data"
            ], 200);
        }
    }

    public function addList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'activity' => 'required',
            'user_id' => "nullable"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "status" => $validator->failed(),
                "data" => $validator->errors()
            ]);
        }

        $input = $request->all();
        if ($input) {
            todolist::create($input);
            return response()->json([
                "status" => 200,
                "message" => "Successfully Add Data"
            ]);
        }
    }

    public function deleteList($id)
    {
        $data = todolist::find($id);
        if (!$data) {
            return response()->json([
                "status" => false,
                "message" => "Data List Not Found"
            ], 404);
        } else {
            $data->delete();
            return response()->json([
                "status" => true,
                "message" => "Data successfully Deleted"
            ], 200);
        }
    }
}

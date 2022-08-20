<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapters;
use App\Models\Courses;

use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            't_courses_id' => 'required|integer',

        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $courseId = $request->input('t_courses_id');

        $course = Courses::find($courseId);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found',
            ], 404);
        }

        $chapter = Chapters::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'chapter was created successfully',
            'data' => $chapter,
        ]);
    }
}

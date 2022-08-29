<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapters;
use App\Models\Courses;
use App\Models\Lessons;
use Illuminate\Support\Facades\Validator;


class LessonController extends Controller
{
    //
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'video_url' => 'required|string',
            't_chapters_id' => 'required|integer',

        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $t_chapters_id = $request->input('t_chapters_id');
        $chapter = Chapters::find($t_chapters_id);

        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found',
            ], 404);
        }

        $lesson = Lessons::create($data);
        return response()->json([
            'status' => 'success',
            'message' => 'lesson added successfully',
            'data' => $lesson,
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            'video_url' => 'string',
            't_chapters_id' => 'integer',

        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $lesson = Lessons::find($id);

        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'lesson not found',
            ], 404);
        }

        $t_chapters_id = $request->input('t_chapters_id');

        if ($t_chapters_id) {
            $chapter = Chapters::find($t_chapters_id);

            if (!$chapter) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'chapter not found',
                ], 404);
            }
        }

        $lesson->fill($data);
        $lesson->save();

        return response()->json([
            'status' => 'success',
            'message' => 'lesson added successfully',
            'data' => $lesson,
        ]);
    }
}

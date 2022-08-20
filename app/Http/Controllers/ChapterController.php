<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapters;
use App\Models\Courses;

use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    public function index(Request $request)
    {
        $chapters = Chapters::query();

        $chapterId = $request->query('t_chapters_id');
        $chapters->when($chapterId, function ($query) use ($chapterId) {
            return $query->where("t_chapters_id", "=", $chapterId);
        });

        $name = $request->query('name');
        $chapters->when($name, function ($query) use ($name) {
            return $query->whereRaw("name LIKE '%" . strtolower($name) . "%'");
        });


        return response()->json([
            'status' => 'success',
            'message' => $chapters->get(),
        ]);
    }

    public function show($id)
    {
        $chapter = Chapters::find($id);
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => $chapter,
        ]);
    }

    public function destroy($id)
    {
        $chapter = Chapters::find($id);
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found',
            ], 404);
        }

        $chapter->delete($id);
        return response()->json([
            'status' => 'success',
            'message' => "chapter was deleted successfully",
        ]);
    }

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

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            't_courses_id' => 'integer',

        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $chapter = Chapters::find($id);

        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found',
            ], 404);
        }

        /* Chapter found in database */

        // Check t_courses_id

        $courseId = $request->input('t_courses_id');

        if ($courseId) {
            $course = Courses::find($courseId);

            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'course not found',
                ], 404);
            }
        }




        $chapter->fill($data);

        $chapter->save();

        return response()->json([
            'status' => 'success',
            'message' => 'chapter was updated successfully',
            'data' => $chapter,
        ]);
    }
}

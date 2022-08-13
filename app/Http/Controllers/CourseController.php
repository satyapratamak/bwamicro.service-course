<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Courses;
use App\Models\Mentor;

class CourseController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'is_certificate' => 'required|boolean',
            'thumbnail' => 'string|url',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,beginner,intermediate,advance',
            't_mentors_id' => 'required|integer',
            'description' => 'string',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        // NO ERROR
        $mentorId = $request->input('t_mentors_id');
        $mentor = Mentor::find($mentorId);
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found',
            ], 404);
        }

        $course = Courses::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Course was created successfully',
            'data' => $course,
        ]);
    }
}

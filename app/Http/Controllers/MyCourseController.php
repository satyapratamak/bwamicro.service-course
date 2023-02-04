<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Courses;
use App\Models\MyCourse;

class MyCourseController extends Controller
{

    public function index(Request $request)
    {
        $myCourses = MyCourse::query()->with('courses');

        $userID = $request->query('user_id');

        $myCourses->when($userID, function ($query) use ($userID) {
            return $query->where('user_id', '=', $userID);
        });

        $courseID = $request->query('t_courses_id');
        $myCourses->when($courseID, function ($query) use ($courseID) {
            return $query->where('t_courses_id', '=', $courseID);
        });

        return response()->json([
            'status' => 'success',

            'data' => $myCourses->get(),
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            't_courses_id' => 'required|integer',
            'user_id' => 'required|integer',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $courseID = $request->input('t_courses_id');
        $course = Courses::find($courseID);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found',
            ], 404);
        }

        $userID = $request->input('user_id');
        $user = getUser($userID);

        if ($user['status'] === 'error') {
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message'],
            ], $user['http_code']);
        }

        $isExistMyCourse = MyCourse::where('t_courses_id', '=', $courseID)
            ->where('user_id', '=', $userID)->exists();

        if ($isExistMyCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'user already taken the course',

            ], 409);
        }
        $myCourse = MyCourse::create($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Course was successfully add to my profile',
            'data' => $myCourse,
        ]);
    }

    public function createPremiumAccess(Request $request)
    {
        $data = $request->all();
        $myCourse = MyCourse::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $myCourse,
        ]);
    }
}

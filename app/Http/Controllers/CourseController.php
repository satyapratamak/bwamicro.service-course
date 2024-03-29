<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Courses;
use App\Models\Mentor;
use App\Models\Review;
use App\Models\MyCourse;
use App\Models\Chapters;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Courses::query();

        $q = $request->query('q');
        $status = $request->query('status');

        $courses->when($q, function ($query) use ($q) {
            return $query->whereRaw("name LIKE '%" . strtolower($q) . "%'");
        });

        $courses->when($status, function ($query) use ($status) {
            return $query->where("status", "=", $status);
        });

        return response()->json([
            'status' => 'success',
            'data' => $courses->paginate(10),
        ]);
    }

    public function show($id)
    {
        $course = Courses::with('chapters.lessons')->with('mentor')->with('images')->find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found',
            ], 404);
        }



        $reviews = Review::where('t_courses_id', '=', $id)->get()->toArray();

        if (count($reviews) > 0) {
            $userIds = array_column($reviews, 'user_id');
            $users = getUserByIds($userIds);

            if ($users['status'] === 'error') {
                $reviews = [];
            } else {
                foreach ($reviews as $key => $review) {
                    $userIndex = array_search($review['user_id'], array_column($users['data'], 'id'));
                    $reviews[$key]['users'] = $users['data'][$userIndex];
                }
            }
        }

        $totalStudent = MyCourse::where('t_courses_id', '=', $id)->count();
        $totalVideos = Chapters::where('t_courses_id', '=', $id)->withCount('lessons')->get()->toArray();
        $finalTotalVideos = array_sum(array_column($totalVideos, 'lessons_count'));

        $course['reviews'] = $reviews;
        $course['total_student'] = $totalStudent;
        $course['total_videos'] = $finalTotalVideos;

        return response()->json([
            'status' => 'success',
            'data' => $course,
        ]);
    }

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

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            'is_certificate' => 'boolean',
            'thumbnail' => 'string|url',
            'type' => 'in:free,premium',
            'status' => 'in:draft,published',
            'price' => 'integer',
            'level' => 'in:all-level,beginner,intermediate,advance',
            't_mentors_id' => 'integer',
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

        $course = Courses::find($id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found',
            ], 404);
        }

        $mentorId = $request->input('t_mentors_id');
        if ($mentorId) {
            // Mentor ID found in requests
            $mentor = Mentor::find($mentorId);
            if (!$mentor) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'mentor not found',
                ], 404);
            }
        }

        $course->fill($data);
        $course->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Course was updated successfully',
            'data' => $course,
        ]);
    }

    public function destroy($id)
    {
        $course = Courses::find($id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found',
            ], 404);
        }

        //Courses::destroy($id);
        $course->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Course was deleted successfully',

        ]);
    }
}

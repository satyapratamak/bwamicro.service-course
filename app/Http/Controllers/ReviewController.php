<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Courses;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'user_id' => 'required|integer',
            't_courses_id' => 'required|integer',
            'ratings' => 'required|integer|min:1|max:5',
            'note' => 'string',
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

        $isExistReview = Review::where('t_courses_id', '=', $courseID)
            ->where('user_id', '=', $userID)->exists();

        if ($isExistReview) {
            return response()->json([
                'status' => 'error',
                'message' => 'review already exists',
            ], 409);
        }

        $review = Review::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Review was added successfully',
            'data' => $review,
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [

            'ratings' => 'integer|min:1|max:5',
            'note' => 'string',
        ];

        $data = $request->except('user_id', 't_courses_id');
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $review = Review::find($id);

        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'review not found',
            ], 404);
        }

        $review->fill($data);
        $review->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Review was updated successfully',
            'data' => $review,
        ]);
    }

    public function destroy($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'review not found',
            ], 404);
        }

        $review->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Review was deleted successfully',
        ]);
    }
}

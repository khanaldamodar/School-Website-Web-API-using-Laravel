<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    // List all courses
    public function index()
    {
        $courses = Course::with('teachers')->latest()->get();
        return response()->json($courses);
    }

    // Show a single course
    public function show($id)
    {
        $course = Course::with('teachers')->find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return response()->json($course);
    }

    // Store a new course
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|unique:courses,name',
            'description'     => 'nullable|string',
            'curriculum'      => 'nullable|string',
            'duration'        => 'nullable|string',
            'addmission_info' => 'nullable|string',
            'teacher_ids'     => 'nullable|array',
            'teacher_ids.*'   => 'exists:teachers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $course = Course::create($request->only([
            'name', 'description', 'curriculum', 'duration', 'addmission_info'
        ]));

        if ($request->has('teacher_ids')) {
            $course->teachers()->sync($request->input('teacher_ids'));
        }

        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course->load('teachers')
        ], 201);
    }

    // Update a course
    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'            => 'sometimes|required|string|unique:courses,name,' . $id,
            'description'     => 'nullable|string',
            'curriculum'      => 'nullable|string',
            'duration'        => 'nullable|string',
            'addmission_info' => 'nullable|string',
            'teacher_ids'     => 'nullable|array',
            'teacher_ids.*'   => 'exists:teachers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $course->update($request->only([
            'name', 'description', 'curriculum', 'duration', 'addmission_info'
        ]));

        if ($request->has('teacher_ids')) {
            $course->teachers()->sync($request->input('teacher_ids'));
        }

        return response()->json([
            'message' => 'Course updated successfully',
            'course' => $course->load('teachers')
        ]);
    }

    // Delete a course
    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $course->teachers()->detach(); // optional: clean pivot
        $course->delete();

        return response()->json(['message' => 'Course deleted successfully']);
    }
}

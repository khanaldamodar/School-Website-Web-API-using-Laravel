<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $teachers = Teacher::with('subjects')->get();
            if ($teachers->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No teachers found'
                ], 404);
            }
            return response()->json([
                'status' => true,
                'data' => $teachers
            ], 200);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching teachers',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $teachersInfo = $request->all();

        $validator = Validator::make($teachersInfo, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        try {

              // Handle image upload
        $imagePath = null;
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imageName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension(); // Unique name
            $imagePath = $image->storeAs('teachers/profile_pictures', $imageName, 'public'); // Stores in /storage/app/public/teachers/profile_pictures
        }

            $teacher = Teacher::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'qualification' => $request->qualification,
                'bio' => $request->bio,
                'profile_picture' => $imagePath,                
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);

            $teacher->subjects()->attach($request->subject_ids);

            return response()->json([
                'status' => true,
                'message' => 'Teacher created successfully',
                'data' => $teacher->load('subjects') // include subjects in response
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while creating the teacher',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $teacher = Teacher::with('subjects')->findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => $teacher
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Teacher not found',
                'error' => $th->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $teachersInfo = $request->all();

        $validator = Validator::make($teachersInfo, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $teacher = Teacher::findOrFail($id);
            $teacher->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'qualification' => $request->qualification,
                'bio' => $request->bio,
                'profile_picture' => $request->profile_picture,
                'updated_by' => auth()->id()
            ]);

            $teacher->subjects()->sync($request->subject_ids);

            return response()->json([
                'status' => true,
                'message' => 'Teacher updated successfully',
                'data' => $teacher->load('subjects')
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the teacher',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $teacher->delete();

            return response()->json([
                'status' => true,
                'message' => 'Teacher deleted successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the teacher',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}

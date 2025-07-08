<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Subject::all();
        if ($subjects->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No subjects found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $subjects
        ], 200);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subjectData = $request->all();

        $validator = Validator::make($subjectData, [
            'name' => 'required|string|max:255|unique:subjects,name',
            'code' => 'nullable|string|max:50|unique:subjects,code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $subject = Subject::create($subjectData);

        return response()->json([
            'status' => true,
            'data' => $subject
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json([
                'status' => false,
                'message' => 'Subject not found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $subject
        ], 200);
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
        $subjectData = $request->all();

        $validator = Validator::make($subjectData, [
            'name' => 'required|string|max:255|unique:subjects,name,' . $id,
            'code' => 'nullable|string|max:50|unique:subjects,code,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json([
                'status' => false,
                'message' => 'Subject not found'
            ], 404);
        }

        $subject->update($subjectData);

        return response()->json([
            'status' => true,
            'data' => $subject
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json([
                'status' => false,
                'message' => 'Subject not found'
            ], 404);
        }

        $subject->delete();

        return response()->json([
            'status' => true,
            'message' => 'Subject deleted successfully'
        ], 200);
    }
}

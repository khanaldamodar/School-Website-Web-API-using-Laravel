<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SchoolInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $information = SchoolInformation::all();
            if ($information->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No school information found'
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => 'School information retrieved successfully',
                'data' => $information
            ], 200);
            
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving school information',
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
        $schoolInformation = $request->all();

        $validator = Validator::make($schoolInformation, [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'school_start_time' => 'nullable|date_format:H:i',
            'school_end_time' => 'nullable|date_format:H:i',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $schoolInformation['created_by'] = auth()->id();
            $schoolInformation['updated_by'] = auth()->id();

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/school_logos'), $filename);
                $schoolInformation['logo'] = 'uploads/school_logos/' . $filename;
            }

            $schoolInfo = SchoolInformation::create($schoolInformation);

            return response()->json([
                'status' => true,
                'message' => 'School information created successfully',
                'data' => $schoolInfo
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating school information',
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
            $schoolInfo = SchoolInformation::findOrFail($id);
            return response()->json([
                'status' => true,
                'message' => 'School information retrieved successfully',
                'data' => $schoolInfo
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving school information',
                'error' => $th->getMessage()
            ], 500);
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
    $schoolInformation = $request->all();

    $validator = Validator::make($schoolInformation, [
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'phone' => 'required|string|max:15',
        'email' => 'required|email|max:255',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'school_start_time' => 'nullable|date_format:H:i',
        'school_end_time' => 'nullable|date_format:H:i',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $schoolInfo = SchoolInformation::findOrFail($id);
        $schoolInformation['updated_by'] = auth()->id();

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            //  Delete old logo file if exists
            if ($schoolInfo->logo && file_exists(public_path($schoolInfo->logo))) {
                unlink(public_path($schoolInfo->logo));
            }

            // Upload new logo
            $file = $request->file('logo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/school_logos'), $filename);
            $schoolInformation['logo'] = 'uploads/school_logos/' . $filename;
        }

        $schoolInfo->update($schoolInformation);

        return response()->json([
            'status' => true,
            'message' => 'School information updated successfully',
            'data' => $schoolInfo
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => 'Error updating school information',
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
            $schoolInfo = SchoolInformation::findOrFail($id);
            $schoolInfo->delete();

            return response()->json([
                'status' => true,
                'message' => 'School information deleted successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting school information',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}

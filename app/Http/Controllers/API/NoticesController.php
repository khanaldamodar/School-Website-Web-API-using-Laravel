<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NoticesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notices = Notices::all();

        if ($notices->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No notices found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $notices
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
        $noticeData = $request->all();

        $validator = Validator::make($noticeData, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notice_date' => 'nullable|date',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('notices', $filename, 'public'); // stored in storage/app/public/notices
            $noticeData['image'] = $path;
        }

        // Create the notice
        $notice = Notices::create($noticeData);

        return response()->json([
            'status' => true,
            'message' => 'Notice created successfully',
            'data' => $notice
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
        $notice = Notices::find($id);

        if (!$notice) {
            return response()->json([
                'status' => false,
                'message' => 'Notice not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $notice
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
        $noticeData = $request->all();

        $validator = \Validator::make($noticeData, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notice_date' => 'required|date',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $notice = Notices::find($id);

        if (!$notice) {
            return response()->json([
                'status' => false,
                'message' => 'Notice not found'
            ], 404);
        }

        $notice->update($noticeData);

        return response()->json([
            'status' => true,
            'data' => $notice
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
        $notice = Notices::find($id);

        if (!$notice) {
            return response()->json([
                'status' => false,
                'message' => 'Notice not found'
            ], 404);
        }

        $notice->delete();

        return response()->json([
            'status' => true,
            'message' => 'Notice deleted successfully'
        ], 200);
    }
}

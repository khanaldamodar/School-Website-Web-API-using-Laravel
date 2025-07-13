<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galleries = Gallery::all();

        if($galleries->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No galleries found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $galleries
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
    $validator = \Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    // Handle image upload
    $imagePath = null;
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
        $imagePath = $image->storeAs('gallery/images', $imageName, 'public'); // stored in storage/app/public/gallery/images
    }

    $gallery = new Gallery();
    $gallery->title = $request->title;
    $gallery->description = $request->description;
    $gallery->image = $imagePath;
    $gallery->save();

    return response()->json([
        'status' => true,
        'message' => 'Image uploaded successfully',
        'data' => $gallery,
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
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json([
                'status' => false,
                'message' => 'Gallery not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $gallery
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
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json([
                'status' => false,
                'message' => 'Gallery not found'
            ], 404);
        }

        $galleryData = $request->all();

        $validator = \Validator::make($galleryData, [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'sometimes|required|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $gallery->update($galleryData);

        return response()->json([
            'status' => true,
            'data' => $gallery
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
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json([
                'status' => false,
                'message' => 'Gallery not found'
            ], 404);
        }

        $gallery->delete();

        return response()->json([
            'status' => true,
            'message' => 'Gallery deleted successfully'
        ], 200);
    }
}

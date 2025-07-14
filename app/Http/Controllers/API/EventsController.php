<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Events::all();

        if ($events->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No events found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $events
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
    $eventData = $request->all();

    $validator = Validator::make($eventData, [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'event_date' => 'required|date',
        'location' => 'nullable|string|max:255',
        'organizer' => 'nullable|string|max:255',
        'about_event' => 'nullable|string',
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
        $path = $image->storeAs('events', $filename, 'public'); // stored in storage/app/public/events
        $eventData['image'] = $path;
    }

    // Create event
    $event = Events::create($eventData);

    return response()->json([
        'status' => true,
        'message' => 'Event created successfully',
        'data' => $event
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
        $event = Events::find($id);

        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $event
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
        $event = Events::find($id);

        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ], 404);
        }

        $eventData = $request->all();

        $validator = Validator::make($eventData, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'about_event' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $event->update($eventData);
        return response()->json([
            'status' => true,
            'message' => 'Event updated successfully',
            'data' => $event
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
        $event = Events::find($id);

        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ], 404);
        }

        $event->delete();
        return response()->json([
            'status' => true,
            'message' => 'Event deleted successfully'
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Events;
use App\Models\Notices;
use App\Models\Teacher;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
      public function index()
    {
        try {
            return response()->json([
                'status' => true,
                'data' => [
                    'teachers' => Teacher::count(),
                    'courses' => Course::count(),
                    'events' => Events::count(),
                    'notices' => Notices::count(),
                  
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to load dashboard data',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}

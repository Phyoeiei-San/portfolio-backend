<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'total_projects' => Project::count(),
            'featured_projects' => Project::where('featured', true)->count(),
            'total_messages' => Message::count(),
            'unread_messages' => Message::where('is_read', false)->count(),
            'recent_projects' => Project::latest()->take(5)->get(),
            'recent_messages' => Message::latest()->take(5)->get(),
        ]);
    }
}

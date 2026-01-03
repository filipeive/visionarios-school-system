<?php

namespace App\Http\Controllers;

use App\Models\MaterialList;
use Illuminate\Http\Request;

class PublicInfoController extends Controller
{
    public function materialLists()
    {
        $materialLists = MaterialList::where('academic_year', 2026)->get();
        return view('public.material-lists', compact('materialLists'));
    }

    public function announcements()
    {
        // Assuming there's a Communication or Announcement model
        // For now, we'll use a placeholder or check if Communications table exists
        $announcements = \App\Models\Communication::where('is_public', true)->latest()->get();
        return view('public.announcements', compact('announcements'));
    }
}

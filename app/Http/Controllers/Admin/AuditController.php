<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
    {
        $this->authorize('view_audit_logs'); // Ensure you have this permission or use a generic admin check

        $activities = Activity::with('causer')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.audit.index', compact('activities'));
    }
}
